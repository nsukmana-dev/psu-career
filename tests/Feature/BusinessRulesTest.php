<?php

use App\Enums\CompanyStatus;
use App\Enums\EmploymentType;
use App\Enums\ItemStatus;
use App\Enums\JobInterest;
use App\Enums\RequestStatus;
use App\Enums\VerificationStatus;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\ContactRequestItem;
use App\Models\SavedSearch;
use App\Models\Student;

/*
 * One test per business rule in spec section 7. Most of these are already
 * exercised from other angles elsewhere in the suite — these confirm the
 * rule itself, through the real HTTP stack, so each rule has a single
 * unambiguous source of truth to point at.
 */

test('rule 1: a company only ever sees real contact info after an item is approved for them', function () {
    $student = Student::factory()->create([
        'verification_status' => VerificationStatus::Verified,
        'open_to_contact' => true,
        'phone' => '089911112222',
    ]);
    $company = Company::factory()->create();

    $this->actingAs($company->user)->get(route('company.candidates.index'))
        ->assertOk()
        ->assertDontSee('089911112222')
        ->assertDontSee($student->user->email);

    $contactRequest = ContactRequest::create([
        'request_number' => 'CR-RULE1',
        'company_id' => $company->id,
        'position_title' => 'Staff',
        'employment_type' => EmploymentType::Fulltime,
        'work_location' => 'Bekasi',
        'message' => 'Test.',
        'status' => RequestStatus::Pending,
    ]);
    $item = ContactRequestItem::create(['contact_request_id' => $contactRequest->id, 'student_id' => $student->id, 'status' => ItemStatus::Approved, 'decided_at' => now()]);

    $this->actingAs($company->user)->get(route('company.contacts.index'))
        ->assertSee('089911112222');
});

test('rule 2: search is scoped to verified, open, active, non-deleted students through the real route', function () {
    // Names are forced (rather than left to the factory's random Faker name) so the
    // assertions below can't flake on two fixtures coincidentally sharing a first name.
    $visible = Student::factory()->create(['verification_status' => VerificationStatus::Verified, 'open_to_contact' => true]);
    $visible->user->update(['name' => 'Kandidat Terlihat']);
    $rejected = Student::factory()->create(['verification_status' => VerificationStatus::Rejected, 'open_to_contact' => true]);
    $rejected->user->update(['name' => 'Kandidat Ditolak']);
    $closed = Student::factory()->create(['verification_status' => VerificationStatus::Verified, 'open_to_contact' => false]);
    $closed->user->update(['name' => 'Kandidat Tertutup']);
    $company = Company::factory()->create();

    $response = $this->actingAs($company->user)->get(route('company.candidates.index'));

    $response->assertOk()
        ->assertSee('Kandidat')
        ->assertDontSee('Ditolak')
        ->assertDontSee('Tertutup');
});

test('rule 3: a verified students gpa only changes once an admin approves the update request', function () {
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Verified, 'gpa' => 3.0]);

    $this->actingAs($student->user)->patch(route('student.profile.update'), [
        'name' => $student->user->name,
        'study_program_id' => $student->study_program_id,
        'entry_year' => $student->entry_year,
        'current_semester' => $student->current_semester,
        'gpa' => '3.90',
        'gender' => $student->gender->value,
    ])->assertSessionHasNoErrors();

    expect($student->fresh()->gpa)->toEqual('3.00');

    $admin = \App\Models\User::factory()->admin()->create();
    $updateRequest = $student->updateRequests()->where('status', 'pending')->firstOrFail();
    $this->actingAs($admin)->post(route('admin.update-requests.approve', $updateRequest), ['note' => null]);

    expect((float) $student->fresh()->gpa)->toBe(3.9);
});

test('rule 4: turning off open_to_contact removes a student from search but keeps their already shared contact', function () {
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Verified, 'open_to_contact' => true]);
    $company = Company::factory()->create();

    $contactRequest = ContactRequest::create([
        'request_number' => 'CR-RULE4',
        'company_id' => $company->id,
        'position_title' => 'Staff',
        'employment_type' => EmploymentType::Fulltime,
        'work_location' => 'Bekasi',
        'message' => 'Test.',
        'status' => RequestStatus::Pending,
    ]);
    ContactRequestItem::create(['contact_request_id' => $contactRequest->id, 'student_id' => $student->id, 'status' => ItemStatus::Approved, 'decided_at' => now()]);

    $this->actingAs($company->user)->get(route('company.candidates.index'))->assertSee(str($student->user->name)->before(' '));

    $this->actingAs($student->user)->put(route('student.preferences.update'), [
        'job_interest' => JobInterest::Fulltime->value,
        'willing_to_relocate' => false,
        'open_to_contact' => false,
    ]);

    $this->actingAs($company->user)->get(route('company.candidates.index'))->assertDontSee(str($student->user->name)->before(' '));
    $this->actingAs($company->user)->get(route('company.contacts.index'))->assertSee($student->user->name);
});

test('rule 5: the form rejects more than 20 candidates before it ever reaches the service', function () {
    $company = Company::factory()->create();
    $students = Student::factory()->count(21)->create(['verification_status' => VerificationStatus::Verified, 'open_to_contact' => true]);

    $this->actingAs($company->user)->post(route('company.contact-requests.store'), [
        'position_title' => 'Staff',
        'employment_type' => EmploymentType::Fulltime->value,
        'student_ids' => $students->pluck('id')->all(),
    ])->assertSessionHasErrors('student_ids');
});

test('rule 6: a suspended companys pending contact request item cannot be processed by admin', function () {
    $company = Company::factory()->create();
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Verified, 'open_to_contact' => true]);
    $contactRequest = ContactRequest::create([
        'request_number' => 'CR-RULE6',
        'company_id' => $company->id,
        'position_title' => 'Staff',
        'employment_type' => EmploymentType::Fulltime,
        'work_location' => 'Bekasi',
        'message' => 'Test.',
        'status' => RequestStatus::Pending,
    ]);
    $item = ContactRequestItem::create(['contact_request_id' => $contactRequest->id, 'student_id' => $student->id, 'status' => ItemStatus::Pending]);

    $company->update(['status' => CompanyStatus::Suspended]);
    $admin = \App\Models\User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.contact-request-items.approve', $item))
        ->assertSessionHas('error');

    expect($item->fresh()->status)->toBe(ItemStatus::Pending);
});

test('rule 7: a company cannot apply another companys saved search', function () {
    $owner = Company::factory()->create();
    $intruder = Company::factory()->create();
    $savedSearch = SavedSearch::create(['company_id' => $owner->id, 'name' => 'My Search', 'filters' => ['min_gpa' => 3.5]]);

    $this->actingAs($intruder->user)->get(route('company.saved-searches.apply', $savedSearch))->assertForbidden();
});

test('rule 8: certificate files are not reachable without authentication', function () {
    $student = Student::factory()->create();
    $certificate = $student->certificates()->create([
        'name' => 'Sertifikat Laravel',
        'issuer' => 'Laravel Academy',
        'issued_at' => now(),
        'file_path' => 'certificates/'.$student->id.'/dummy.pdf',
    ]);

    $this->get(route('certificates.download', $certificate))->assertRedirect(route('login'));
});
