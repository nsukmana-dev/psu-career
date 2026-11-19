<?php

use App\Enums\ApprovalStatus;
use App\Enums\VerificationStatus;
use App\Models\Certificate;
use App\Models\Student;
use App\Models\User;

test('admin can approve a pending student profile', function () {
    $admin = User::factory()->admin()->create();
    $student = Student::factory()->pending()->create();

    $this->actingAs($admin)->post(route('admin.verifications.approve', $student))
        ->assertRedirect(route('admin.verifications.index'));

    $fresh = $student->fresh();
    expect($fresh->verification_status)->toBe(VerificationStatus::Verified)
        ->and($fresh->verified_by)->toBe($admin->id)
        ->and($fresh->verified_at)->not->toBeNull();
});

test('admin can reject a pending student profile with a reason', function () {
    $admin = User::factory()->admin()->create();
    $student = Student::factory()->pending()->create();

    $this->actingAs($admin)->post(route('admin.verifications.reject', $student), [
        'reason' => 'Data belum lengkap.',
    ])->assertRedirect(route('admin.verifications.index'));

    $fresh = $student->fresh();
    expect($fresh->verification_status)->toBe(VerificationStatus::Rejected)
        ->and($fresh->rejection_reason)->toBe('Data belum lengkap.');
});

test('admin approving an update request mutates the real student column', function () {
    $admin = User::factory()->admin()->create();
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Verified, 'gpa' => 3.0]);
    $request = $student->updateRequests()->create([
        'changes' => ['gpa' => ['old' => '3.00', 'new' => '3.75']],
        'status' => ApprovalStatus::Pending,
    ]);

    $this->actingAs($admin)->post(route('admin.update-requests.approve', $request))
        ->assertRedirect(route('admin.update-requests.index'));

    expect((float) $student->fresh()->gpa)->toBe(3.75)
        ->and($request->fresh()->status)->toBe(ApprovalStatus::Approved);
});

test('admin rejecting an update request leaves the student column untouched', function () {
    $admin = User::factory()->admin()->create();
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Verified, 'gpa' => 3.0]);
    $request = $student->updateRequests()->create([
        'changes' => ['gpa' => ['old' => '3.00', 'new' => '3.75']],
        'status' => ApprovalStatus::Pending,
    ]);

    $this->actingAs($admin)->post(route('admin.update-requests.reject', $request), [
        'note' => 'IPK tidak sesuai transkrip.',
    ])->assertRedirect(route('admin.update-requests.index'));

    expect((float) $student->fresh()->gpa)->toBe(3.0)
        ->and($request->fresh()->status)->toBe(\App\Enums\ApprovalStatus::Rejected);
});

test('certificate download is restricted to the owner and admin', function () {
    $owner = Student::factory()->create();
    $otherStudent = Student::factory()->create();
    $admin = User::factory()->admin()->create();
    $certificate = Certificate::factory()->for($owner)->create(['file_path' => 'certificates/1/dummy.pdf']);

    \Illuminate\Support\Facades\Storage::disk('local')->put('certificates/1/dummy.pdf', 'dummy');

    $this->actingAs($owner->user)->get(route('certificates.download', $certificate))->assertOk();
    $this->actingAs($otherStudent->user)->get(route('certificates.download', $certificate))->assertForbidden();
    $this->actingAs($admin)->get(route('certificates.download', $certificate))->assertOk();
});
