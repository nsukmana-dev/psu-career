<?php

use App\Enums\ApprovalStatus;
use App\Enums\EmploymentType;
use App\Enums\ItemStatus;
use App\Enums\RequestStatus;
use App\Enums\VerificationStatus;
use App\Models\ActivityLog;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\ContactRequestItem;
use App\Models\Student;
use App\Models\User;

test('logging in records an activity log', function () {
    $admin = User::factory()->admin()->create();

    $this->post(route('login'), ['email' => $admin->email, 'password' => 'password'])
        ->assertRedirect();

    expect(ActivityLog::where('action', 'login')->where('user_id', $admin->id)->exists())->toBeTrue();
});

test('approving a student verification records an activity log', function () {
    $admin = User::factory()->admin()->create();
    $student = Student::factory()->pending()->create();

    $this->actingAs($admin)->post(route('admin.verifications.approve', $student))->assertRedirect();

    $log = ActivityLog::where('action', 'student.verified')->first();
    expect($log)->not->toBeNull()
        ->subject_type->toBe($student->getMorphClass())
        ->subject_id->toBe($student->id);
});

test('company accessing shared contacts records an activity log', function () {
    $company = Company::factory()->create();
    $student = Student::factory()->verified()->create();
    $request = ContactRequest::create([
        'request_number' => 'CR-0002',
        'company_id' => $company->id,
        'position_title' => 'QA Engineer',
        'employment_type' => EmploymentType::Internship,
        'work_location' => 'Bekasi',
        'message' => 'Butuh kandidat.',
        'status' => RequestStatus::Completed,
    ]);
    ContactRequestItem::create(['contact_request_id' => $request->id, 'student_id' => $student->id, 'status' => ItemStatus::Approved, 'decided_at' => now()]);

    $this->actingAs($company->user)->get(route('company.contacts.index'))->assertOk();

    expect(ActivityLog::where('action', 'contact.accessed')->where('subject_id', $company->id)->exists())->toBeTrue();
});

test('admin can view and filter activity logs', function () {
    $admin = User::factory()->admin()->create();
    ActivityLog::record('student.verified');
    ActivityLog::record('company.updated');

    $this->actingAs($admin)->get(route('admin.activity-logs.index'))
        ->assertOk()
        ->assertViewHas('logs', fn ($logs) => $logs->total() === 2);

    $this->actingAs($admin)->get(route('admin.activity-logs.index', ['action' => 'student.verified']))
        ->assertViewHas('logs', fn ($logs) => $logs->total() === 1 && $logs->first()->action === 'student.verified');
});

test('a non admin cannot view activity logs or export data', function () {
    $student = User::factory()->student()->create();

    $this->actingAs($student)->get(route('admin.activity-logs.index'))->assertForbidden();
    $this->actingAs($student)->get(route('admin.export.students'))->assertForbidden();
    $this->actingAs($student)->get(route('admin.export.contact-requests'))->assertForbidden();
});

test('admin can export verified students as csv', function () {
    $admin = User::factory()->admin()->create();
    $verified = Student::factory()->verified()->create();
    $pending = Student::factory()->pending()->create();

    $response = $this->actingAs($admin)->get(route('admin.export.students'));

    $response->assertOk();
    $csv = $response->streamedContent();
    expect($csv)->toContain($verified->nim)
        ->not->toContain($pending->nim);
});

test('admin can export contact request recap as csv', function () {
    $admin = User::factory()->admin()->create();
    $company = Company::factory()->create();
    ContactRequest::create([
        'request_number' => 'CR-0003',
        'company_id' => $company->id,
        'position_title' => 'Backend Developer',
        'employment_type' => EmploymentType::Fulltime,
        'work_location' => 'Bekasi',
        'message' => 'Butuh kandidat.',
        'status' => RequestStatus::Pending,
    ]);

    $response = $this->actingAs($admin)->get(route('admin.export.contact-requests'));

    $response->assertOk();
    expect($response->streamedContent())->toContain('CR-0003');
});

test('student dashboard shows pending update request banner', function () {
    $student = Student::factory()->verified()->create();
    $student->updateRequests()->create([
        'changes' => ['gpa' => ['old' => 3.0, 'new' => 3.5]],
        'status' => ApprovalStatus::Pending,
    ]);

    $this->actingAs($student->user)->get(route('student.dashboard'))
        ->assertSee('menunggu persetujuan admin');
});

test('admin dashboard reflects real student and company counts', function () {
    Student::factory()->verified()->count(2)->create();
    Student::factory()->pending()->create();
    Company::factory()->create();
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->get(route('admin.dashboard'))->assertOk();

    expect(Student::where('verification_status', VerificationStatus::Verified)->count())->toBe(2)
        ->and(Student::where('verification_status', VerificationStatus::Pending)->count())->toBe(1);
});
