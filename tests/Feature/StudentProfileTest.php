<?php

use App\Enums\VerificationStatus;
use App\Models\Skill;
use App\Models\Student;

test('non-sensitive field updates immediately regardless of verification status', function () {
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Verified]);

    $this->actingAs($student->user)->patch(route('student.profile.update'), [
        'name' => $student->user->name,
        'study_program_id' => $student->study_program_id,
        'entry_year' => $student->entry_year,
        'current_semester' => $student->current_semester,
        'gpa' => (string) $student->gpa,
        'gender' => $student->gender->value,
        'city' => 'Bekasi Baru',
    ])->assertSessionHasNoErrors();

    expect($student->fresh()->city)->toBe('Bekasi Baru');
});

test('sensitive field change on a verified student creates an update request instead of mutating directly', function () {
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Verified, 'gpa' => 3.0]);

    $this->actingAs($student->user)->patch(route('student.profile.update'), [
        'name' => $student->user->name,
        'study_program_id' => $student->study_program_id,
        'entry_year' => $student->entry_year,
        'current_semester' => $student->current_semester,
        'gpa' => '3.80',
        'gender' => $student->gender->value,
    ])->assertSessionHasNoErrors();

    expect((float) $student->fresh()->gpa)->toBe(3.0)
        ->and($student->updateRequests()->where('status', 'pending')->count())->toBe(1)
        ->and($student->updateRequests()->first()->changes)->toHaveKey('gpa');
});

test('sensitive field change on a draft student saves directly without an update request', function () {
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Draft, 'gpa' => 3.0]);

    $this->actingAs($student->user)->patch(route('student.profile.update'), [
        'name' => $student->user->name,
        'study_program_id' => $student->study_program_id,
        'entry_year' => $student->entry_year,
        'current_semester' => $student->current_semester,
        'gpa' => '3.80',
        'gender' => $student->gender->value,
    ])->assertSessionHasNoErrors();

    expect((float) $student->fresh()->gpa)->toBe(3.8)
        ->and($student->updateRequests()->count())->toBe(0);
});

test('updating a profile only ever touches the authenticated users own student record', function () {
    $owner = Student::factory()->create(['city' => 'Jakarta']);
    $intruder = Student::factory()->create(['city' => 'Bandung']);

    // The route has no student-id parameter — it always targets request()->user()->student,
    // so "updating someone else's profile" isn't reachable. Verify that invariant instead.
    $this->actingAs($intruder->user)->patch(route('student.profile.update'), [
        'name' => $intruder->user->name,
        'study_program_id' => $intruder->study_program_id,
        'entry_year' => $intruder->entry_year,
        'current_semester' => $intruder->current_semester,
        'gpa' => (string) $intruder->gpa,
        'gender' => $intruder->gender->value,
        'city' => 'Surabaya',
    ])->assertSessionHasNoErrors();

    expect($intruder->fresh()->city)->toBe('Surabaya')
        ->and($owner->fresh()->city)->toBe('Jakarta');
});

test('profile completion recalculates after adding a skill', function () {
    $student = Student::factory()->create();
    $before = app(\App\Services\ProfileCompletionService::class)->calculate($student->fresh());

    $skill = Skill::factory()->create();

    $this->actingAs($student->user)->post(route('student.skills.store'), [
        'skill_name' => $skill->name,
        'level' => 'basic',
    ])->assertSessionHasNoErrors();

    expect($student->fresh()->profile_completion)->toBeGreaterThan($before);
});

test('submitting a draft profile moves it to pending', function () {
    $student = Student::factory()->create(['verification_status' => VerificationStatus::Draft]);

    $this->actingAs($student->user)->post(route('student.profile.submit'))
        ->assertSessionHasNoErrors();

    expect($student->fresh()->verification_status)->toBe(VerificationStatus::Pending);
});


