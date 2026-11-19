<?php

use App\Models\Company;
use App\Models\Industry;
use App\Models\Skill;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Database\Seeders\DemoSeeder;
use Illuminate\Support\Facades\DB;

test('admin can register a student account directly and an invite is sent', function () {
    $admin = User::factory()->admin()->create();
    $program = StudyProgram::factory()->create();

    $this->actingAs($admin)->post(route('admin.students.store'), [
        'name' => 'Budi Santoso',
        'email' => 'budi@example.com',
        'nim' => '2026123456',
        'study_program_id' => $program->id,
        'entry_year' => 2026,
    ])->assertRedirect(route('admin.students.index'));

    $user = User::where('email', 'budi@example.com')->first();
    expect($user)->not->toBeNull()
        ->role->toBe(\App\Enums\UserRole::Student)
        ->must_change_password->toBeTrue();
    expect($user->student)->not->toBeNull()->nim->toBe('2026123456');
    expect(DB::table('password_reset_tokens')->where('email', 'budi@example.com')->exists())->toBeTrue();
});

test('admin can filter the student list by status, program, and entry year', function () {
    $admin = User::factory()->admin()->create();
    $program = StudyProgram::factory()->create();
    $match = Student::factory()->verified()->create(['study_program_id' => $program->id, 'entry_year' => 2024]);
    $other = Student::factory()->pending()->create();

    $response = $this->actingAs($admin)->get(route('admin.students.index', [
        'status' => 'verified',
        'study_program_id' => $program->id,
        'entry_year' => 2024,
    ]));

    $response->assertOk()->assertViewHas('students', fn ($students) => $students->pluck('id')->contains($match->id)
        && ! $students->pluck('id')->contains($other->id));
});

test('admin can deactivate and reactivate a student account', function () {
    $admin = User::factory()->admin()->create();
    $student = Student::factory()->create();

    $this->actingAs($admin)->post(route('admin.students.toggle-active', $student))->assertRedirect();
    expect($student->user->fresh()->is_active)->toBeFalse();

    $this->post(route('logout'));
    $this->post(route('login'), ['email' => $student->user->email, 'password' => 'password'])
        ->assertSessionHasErrors('email');

    $this->actingAs($admin)->post(route('admin.students.toggle-active', $student))->assertRedirect();
    expect($student->user->fresh()->is_active)->toBeTrue();
});

test('admin can send a password reset link to a student and a company', function () {
    $admin = User::factory()->admin()->create();
    $student = Student::factory()->create();
    $company = Company::factory()->create();

    $this->actingAs($admin)->post(route('admin.students.reset-password', $student))->assertRedirect();
    $this->actingAs($admin)->post(route('admin.companies.reset-password', $company))->assertRedirect();

    expect(DB::table('password_reset_tokens')->where('email', $student->user->email)->exists())->toBeTrue()
        ->and(DB::table('password_reset_tokens')->where('email', $company->user->email)->exists())->toBeTrue();
});

test('a non admin cannot manage students or master data', function () {
    $student = User::factory()->student()->create();

    foreach ([
        'admin.students.index', 'admin.study-programs.index', 'admin.skills.index',
        'admin.industries.index', 'admin.cv-templates.index',
    ] as $route) {
        $this->actingAs($student)->get(route($route))->assertForbidden();
    }
});

test('admin can manage study programs', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.study-programs.store'), [
        'code' => 'TK', 'name' => 'Teknik Komputer', 'level' => 'S1',
    ])->assertRedirect();

    $program = StudyProgram::where('code', 'TK')->firstOrFail();
    expect($program->is_active)->toBeTrue();

    $this->actingAs($admin)->put(route('admin.study-programs.update', $program), [
        'code' => 'TK', 'name' => 'Teknik Komputer Terapan', 'level' => 'S1',
    ])->assertRedirect();
    expect($program->fresh()->name)->toBe('Teknik Komputer Terapan');

    $this->actingAs($admin)->post(route('admin.study-programs.toggle-active', $program))->assertRedirect();
    expect($program->fresh()->is_active)->toBeFalse();
});

test('admin can manage skills including approving student suggested ones', function () {
    $admin = User::factory()->admin()->create();
    $suggested = Skill::create(['name' => 'Skill Usulan', 'is_approved' => false]);

    $this->actingAs($admin)->post(route('admin.skills.approve', $suggested))->assertRedirect();
    expect($suggested->fresh()->is_approved)->toBeTrue();

    $this->actingAs($admin)->post(route('admin.skills.store'), ['name' => 'Skill Baru', 'category' => 'IT'])->assertRedirect();
    $created = Skill::where('name', 'Skill Baru')->firstOrFail();
    expect($created->is_approved)->toBeTrue();

    $this->actingAs($admin)->put(route('admin.skills.update', $created), ['name' => 'Skill Baru 2', 'category' => 'IT'])->assertRedirect();
    expect($created->fresh()->name)->toBe('Skill Baru 2');

    $this->actingAs($admin)->delete(route('admin.skills.destroy', $created))->assertRedirect();
    expect(Skill::find($created->id))->toBeNull();
});

test('admin can manage industries', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)->post(route('admin.industries.store'), ['name' => 'Fintech'])->assertRedirect();
    $industry = Industry::where('name', 'Fintech')->firstOrFail();

    $this->actingAs($admin)->put(route('admin.industries.update', $industry), ['name' => 'Fintech & Perbankan'])->assertRedirect();
    expect($industry->fresh()->name)->toBe('Fintech & Perbankan');

    $this->actingAs($admin)->delete(route('admin.industries.destroy', $industry))->assertRedirect();
    expect(Industry::find($industry->id))->toBeNull();
});

test('admin can update and toggle a cv template', function () {
    $admin = User::factory()->admin()->create();
    $template = \App\Models\CvTemplate::create([
        'name' => 'Klasik', 'slug' => 'klasik-test', 'blade_view' => 'cv.templates.classic-ats', 'is_active' => true, 'sort_order' => 1,
    ]);

    $this->actingAs($admin)->put(route('admin.cv-templates.update', $template), [
        'name' => 'Klasik ATS', 'sort_order' => 2,
    ])->assertRedirect();
    expect($template->fresh())->name->toBe('Klasik ATS')->sort_order->toBe(2);

    $this->actingAs($admin)->post(route('admin.cv-templates.toggle-active', $template))->assertRedirect();
    expect($template->fresh()->is_active)->toBeFalse();
});

test('demo seeder creates the expected dummy data without errors', function () {
    StudyProgram::factory()->count(2)->create();
    Skill::factory()->count(5)->create();

    $this->seed(DemoSeeder::class);

    expect(Student::count())->toBe(50)
        ->and(Company::count())->toBe(5);
});
