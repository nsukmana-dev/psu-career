<?php

use App\Enums\ItemStatus;
use App\Enums\RequestStatus;
use App\Http\Resources\CandidateResource;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\Student;
use App\Models\User;
use App\Notifications\ContactApproved;
use App\Notifications\ContactRequestDecided;
use App\Notifications\NewContactRequest;
use App\Services\ContactRequestService;
use Illuminate\Support\Facades\Notification;

function contactRequestPayload(array $overrides = []): array
{
    return array_merge([
        'position_title' => 'Backend Developer',
        'employment_type' => 'internship',
        'work_location' => 'Jakarta',
        'message' => 'Mohon diproses segera.',
    ], $overrides);
}

test('a request with more than 20 candidates is rejected', function () {
    $company = Company::factory()->create();
    $students = Student::factory()->count(21)->create();

    $this->actingAs($company->user)->post(route('company.contact-requests.store'), [
        ...contactRequestPayload(),
        'student_ids' => $students->pluck('id')->all(),
    ])->assertSessionHasErrors('student_ids');
});

test('a student already approved for a company is excluded from a new request by the same company, but not by another', function () {
    Notification::fake();
    $companyA = Company::factory()->create();
    $companyB = Company::factory()->create();
    $student = Student::factory()->create();

    // Simulate an existing approved item for company A.
    $priorRequest = ContactRequest::create([
        'request_number' => 'REQ-2026-00001',
        'company_id' => $companyA->id,
        'position_title' => 'Prior',
        'employment_type' => 'internship',
        'status' => RequestStatus::Completed,
    ]);
    $priorRequest->items()->create(['student_id' => $student->id, 'status' => ItemStatus::Approved]);

    $this->actingAs($companyA->user)->post(route('company.contact-requests.store'), [
        ...contactRequestPayload(),
        'student_ids' => [$student->id],
    ])->assertSessionHasErrors('student_ids');

    $this->actingAs($companyB->user)->post(route('company.contact-requests.store'), [
        ...contactRequestPayload(),
        'student_ids' => [$student->id],
    ])->assertSessionHasNoErrors();

    expect(ContactRequest::where('company_id', $companyB->id)->exists())->toBeTrue();
});

test('approving an item notifies the student and company, shares the contact, and leaves other companies blind', function () {
    Notification::fake();
    $admin = User::factory()->admin()->create();
    $company = Company::factory()->create();
    $otherCompany = Company::factory()->create();
    $student = Student::factory()->create(['phone' => '081234567890']);

    $this->actingAs($company->user)->post(route('company.contact-requests.store'), [
        ...contactRequestPayload(),
        'student_ids' => [$student->id],
    ])->assertSessionHasNoErrors();

    $item = \App\Models\ContactRequestItem::where('student_id', $student->id)->first();
    $service = app(ContactRequestService::class);
    $service->decideItem($item, $admin, ItemStatus::Approved);

    Notification::assertSentTo($student->user, ContactApproved::class);
    Notification::assertSentTo($company->user, ContactRequestDecided::class);
    Notification::assertSentTo($admin, NewContactRequest::class);

    $ownStatus = (new CandidateResource($student, $company))->resolve()['contact_status'];
    expect($ownStatus)->toBe('shared');

    $otherStatus = (new CandidateResource($student, $otherCompany))->resolve()['contact_status'];
    expect($otherStatus)->toBe('hidden');

    $response = $this->actingAs($company->user)->get(route('company.contacts.index'));
    $response->assertOk()->assertSee('081234567890');
});

test('rejecting an item requires a reason and never exposes contact', function () {
    Notification::fake();
    $admin = User::factory()->admin()->create();
    $company = Company::factory()->create();
    $student = Student::factory()->create();

    $this->actingAs($company->user)->post(route('company.contact-requests.store'), [
        ...contactRequestPayload(),
        'student_ids' => [$student->id],
    ]);

    $item = \App\Models\ContactRequestItem::where('student_id', $student->id)->first();

    $this->actingAs($admin)->post(route('admin.contact-request-items.reject', $item))
        ->assertSessionHasErrors('rejection_reason');

    $this->actingAs($admin)->post(route('admin.contact-request-items.reject', $item), [
        'rejection_reason' => 'Data tidak sesuai kebutuhan.',
    ])->assertRedirect();

    expect($item->fresh()->status)->toBe(ItemStatus::Rejected);

    $status = (new CandidateResource($student, $company))->resolve()['contact_status'];
    expect($status)->toBe('hidden');
});

test('the parent request only completes once every item has been decided', function () {
    $admin = User::factory()->admin()->create();
    $company = Company::factory()->create();
    $studentA = Student::factory()->create();
    $studentB = Student::factory()->create();

    $this->actingAs($company->user)->post(route('company.contact-requests.store'), [
        ...contactRequestPayload(),
        'student_ids' => [$studentA->id, $studentB->id],
    ]);

    $contactRequest = ContactRequest::where('company_id', $company->id)->first();
    $items = $contactRequest->items;

    $service = app(ContactRequestService::class);
    $service->decideItem($items[0], $admin, ItemStatus::Approved);
    expect($contactRequest->fresh()->status)->toBe(RequestStatus::Pending);

    $service->decideItem($items[1], $admin, ItemStatus::Rejected, 'Tidak sesuai.');
    expect($contactRequest->fresh()->status)->toBe(RequestStatus::Completed);
});

test('turning off open_to_contact after approval does not revoke an already shared contact', function () {
    $admin = User::factory()->admin()->create();
    $company = Company::factory()->create();
    $student = Student::factory()->create(['open_to_contact' => true]);

    $this->actingAs($company->user)->post(route('company.contact-requests.store'), [
        ...contactRequestPayload(),
        'student_ids' => [$student->id],
    ]);

    $item = \App\Models\ContactRequestItem::where('student_id', $student->id)->first();
    app(ContactRequestService::class)->decideItem($item, $admin, ItemStatus::Approved);

    $student->update(['open_to_contact' => false]);

    $response = $this->actingAs($company->user)->get(route('company.contacts.index'));
    $response->assertOk()->assertSee($student->user->email);
});

test('a company cannot view or decide another companys contact request', function () {
    $owner = Company::factory()->create();
    $intruder = Company::factory()->create();
    $student = Student::factory()->create();

    $this->actingAs($owner->user)->post(route('company.contact-requests.store'), [
        ...contactRequestPayload(),
        'student_ids' => [$student->id],
    ]);

    $contactRequest = ContactRequest::where('company_id', $owner->id)->first();

    $this->actingAs($intruder->user)->get(route('company.contact-requests.show', $contactRequest))->assertForbidden();
});
