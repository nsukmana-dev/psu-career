<?php

use App\Enums\VerificationStatus;
use App\Http\Resources\CandidateResource;
use App\Models\Company;
use App\Models\ProfileView;
use App\Models\Skill;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use App\Services\CandidateSearchService;

function verifiedOpenStudent(array $overrides = []): Student
{
    return Student::factory()->create(array_merge([
        'verification_status' => VerificationStatus::Verified,
        'open_to_contact' => true,
    ], $overrides));
}

test('only verified, contactable, active, non-deleted students appear in search', function () {
    $visible = verifiedOpenStudent();
    $unverified = verifiedOpenStudent(['verification_status' => VerificationStatus::Pending]);
    $notOpen = verifiedOpenStudent(['open_to_contact' => false]);
    $inactiveUserStudent = verifiedOpenStudent();
    $inactiveUserStudent->user->update(['is_active' => false]);
    $deleted = verifiedOpenStudent();
    $deleted->delete();

    $results = app(CandidateSearchService::class)->search([]);
    $ids = $results->pluck('id');

    expect($ids)->toContain($visible->id)
        ->not->toContain($unverified->id)
        ->not->toContain($notOpen->id)
        ->not->toContain($inactiveUserStudent->id)
        ->not->toContain($deleted->id);
});

test('candidate resource never exposes contact fields', function () {
    $student = verifiedOpenStudent(['phone' => '0811111111']);

    $resolved = (new CandidateResource($student))->resolve();

    expect($resolved)->not->toHaveKeys(['email', 'phone', 'birth_date', 'linkedin_url']);
});

test('the candidate detail page never renders the actual phone or email', function () {
    $student = verifiedOpenStudent(['phone' => '089900011122']);
    $company = Company::factory()->create();

    $response = $this->actingAs($company->user)->get(route('company.candidates.show', $student));

    $response->assertOk();
    expect($response->getContent())
        ->not->toContain('089900011122')
        ->not->toContain($student->user->email);
});

test('skill_mode all requires every selected skill, any requires at least one', function () {
    $skillA = Skill::factory()->create();
    $skillB = Skill::factory()->create();

    $hasBoth = verifiedOpenStudent();
    $hasBoth->skills()->attach([$skillA->id => ['level' => 'basic'], $skillB->id => ['level' => 'basic']]);

    $hasOne = verifiedOpenStudent();
    $hasOne->skills()->attach([$skillA->id => ['level' => 'basic']]);

    $service = app(CandidateSearchService::class);

    $any = $service->search(['skill_ids' => [$skillA->id, $skillB->id], 'skill_mode' => 'any'])->pluck('id');
    expect($any)->toContain($hasBoth->id)->toContain($hasOne->id);

    $all = $service->search(['skill_ids' => [$skillA->id, $skillB->id], 'skill_mode' => 'all'])->pluck('id');
    expect($all)->toContain($hasBoth->id)->not->toContain($hasOne->id);
});

test('min_gpa and study_program filters narrow results', function () {
    $program = StudyProgram::factory()->create();
    $otherProgram = StudyProgram::factory()->create();
    $highGpa = verifiedOpenStudent(['gpa' => 3.8, 'study_program_id' => $program->id]);
    $lowGpa = verifiedOpenStudent(['gpa' => 2.5, 'study_program_id' => $otherProgram->id]);

    $results = app(CandidateSearchService::class)->search(['min_gpa' => 3.5])->pluck('id');
    expect($results)->toContain($highGpa->id)->not->toContain($lowGpa->id);

    $byProgram = app(CandidateSearchService::class)->search(['study_program_ids' => [$program->id]])->pluck('id');
    expect($byProgram)->toContain($highGpa->id)->not->toContain($lowGpa->id);
});

test('viewing a candidate twice the same day only records one profile view', function () {
    $student = verifiedOpenStudent();
    $company = Company::factory()->create();

    $this->actingAs($company->user)->get(route('company.candidates.show', $student));
    $this->actingAs($company->user)->get(route('company.candidates.show', $student));

    expect(ProfileView::where('company_id', $company->id)->where('student_id', $student->id)->count())->toBe(1);
});

test('student dashboard counts distinct companies that viewed this month without naming them', function () {
    $student = verifiedOpenStudent();
    $companyA = Company::factory()->create();
    $companyB = Company::factory()->create();

    ProfileView::create(['company_id' => $companyA->id, 'student_id' => $student->id, 'viewed_at' => now()]);
    ProfileView::create(['company_id' => $companyB->id, 'student_id' => $student->id, 'viewed_at' => now()]);
    ProfileView::create(['company_id' => $companyA->id, 'student_id' => $student->id, 'viewed_at' => now()->subMonths(2)]);

    $response = $this->actingAs($student->user)->get(route('student.dashboard'));

    $response->assertOk()->assertSee('2 perusahaan')
        ->assertDontSee($companyA->name)
        ->assertDontSee($companyB->name);
});
