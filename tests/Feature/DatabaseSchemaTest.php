<?php

use App\Enums\CompanyStatus;
use App\Enums\SkillLevel;
use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\Industry;
use App\Models\Shortlist;
use App\Models\Skill;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

test('all expected tables exist', function () {
    $tables = [
        'users', 'study_programs', 'skills', 'student_skill', 'experiences', 'educations',
        'certificates', 'portfolios', 'student_update_requests', 'cv_templates', 'industries',
        'partnership_applications', 'companies', 'shortlists', 'shortlist_items', 'saved_searches',
        'contact_requests', 'contact_request_items', 'profile_views', 'faqs', 'landing_settings',
        'activity_logs', 'notifications',
    ];

    foreach ($tables as $table) {
        expect(Schema::hasTable($table))->toBeTrue("Table [$table] should exist");
    }
});

test('student belongs to user and study program, and casts enums', function () {
    $program = StudyProgram::factory()->create(['level' => 'S1']);
    $user = User::factory()->student()->create();
    $student = Student::factory()->verified()->create([
        'user_id' => $user->id,
        'study_program_id' => $program->id,
    ]);

    expect($student->user)->toBeInstanceOf(User::class)
        ->and($student->user->is($user))->toBeTrue()
        ->and($student->studyProgram)->toBeInstanceOf(StudyProgram::class)
        ->and($user->student->is($student))->toBeTrue()
        ->and($student->verification_status)->toBe(VerificationStatus::Verified)
        ->and($user->role)->toBe(UserRole::Student);
});

test('student can be attached to skills with a level through the pivot', function () {
    $student = Student::factory()->create();
    $skill = Skill::factory()->create();

    $student->skills()->attach($skill->id, ['level' => SkillLevel::Advanced->value]);

    $attached = $student->skills()->first();

    expect($attached->id)->toBe($skill->id)
        ->and($attached->pivot->level)->toBe(SkillLevel::Advanced->value);
});

test('company belongs to a user and an industry', function () {
    $industry = Industry::factory()->create();
    $company = Company::factory()->create(['industry_id' => $industry->id]);

    expect($company->user)->toBeInstanceOf(User::class)
        ->and($company->user->role)->toBe(UserRole::Company)
        ->and($company->industry->is($industry))->toBeTrue()
        ->and($company->status)->toBe(CompanyStatus::Active);
});

test('shortlist items link a company shortlist to a student with a note', function () {
    $company = Company::factory()->create();
    $student = Student::factory()->create();
    $shortlist = Shortlist::create(['company_id' => $company->id, 'name' => 'Posisi Magang']);

    $item = $shortlist->items()->create(['student_id' => $student->id, 'note' => 'Kandidat kuat']);

    expect($shortlist->items)->toHaveCount(1)
        ->and($item->student->is($student))->toBeTrue()
        ->and($item->shortlist->is($shortlist))->toBeTrue();
});

test('contact request items belong to the parent request and a student', function () {
    $company = Company::factory()->create();
    $student = Student::factory()->create();
    $request = ContactRequest::create([
        'request_number' => 'REQ-2026-00001',
        'company_id' => $company->id,
        'position_title' => 'Backend Developer',
        'employment_type' => 'internship',
        'status' => 'pending',
    ]);

    $item = $request->items()->create(['student_id' => $student->id, 'status' => 'pending']);

    expect($request->items)->toHaveCount(1)
        ->and($item->contactRequest->is($request))->toBeTrue()
        ->and($item->student->is($student))->toBeTrue();
});

test('nim and request_number are unique', function () {
    $program = StudyProgram::factory()->create();
    Student::factory()->create(['nim' => '2021000001', 'study_program_id' => $program->id]);

    expect(fn () => Student::factory()->create(['nim' => '2021000001', 'study_program_id' => $program->id]))
        ->toThrow(\Illuminate\Database\QueryException::class);
});
