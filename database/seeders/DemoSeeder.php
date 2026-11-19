<?php

namespace Database\Seeders;

use App\Enums\EmploymentType;
use App\Enums\ItemStatus;
use App\Enums\RequestStatus;
use App\Enums\SkillLevel;
use App\Models\Company;
use App\Models\ContactRequest;
use App\Models\Skill;
use App\Models\Student;
use Illuminate\Database\Seeder;

/**
 * Dummy data for manual demo/testing only — never run alongside DatabaseSeeder
 * in production. Assumes DatabaseSeeder already ran (study programs, skills).
 */
class DemoSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::factory()->verified()->count(25)->create()
            ->concat(Student::factory()->pending()->count(10)->create())
            ->concat(Student::factory()->rejected()->count(5)->create())
            ->concat(Student::factory()->count(10)->create());

        $skills = Skill::inRandomOrder()->get();
        $students->each(function (Student $student) use ($skills) {
            $student->skills()->attach(
                $skills->random(rand(2, 5))->mapWithKeys(fn ($skill) => [
                    $skill->id => ['level' => collect(SkillLevel::cases())->random()->value],
                ])
            );
        });

        $companies = Company::factory()->count(4)->create()
            ->push(Company::factory()->suspended()->create());

        $verifiedStudents = $students->filter(
            fn (Student $s) => $s->verification_status->value === 'verified' && $s->open_to_contact
        )->values();

        $companies->where('status.value', 'active')->each(function (Company $company) use ($verifiedStudents) {
            $candidates = $verifiedStudents->random(min(3, $verifiedStudents->count()));

            $shortlist = $company->shortlists()->create(['name' => 'Kandidat Pilihan']);
            foreach ($candidates as $student) {
                $shortlist->items()->create(['student_id' => $student->id]);
            }

            $contactRequest = ContactRequest::create([
                'request_number' => 'DEMO-'.$company->id.'-'.now()->timestamp,
                'company_id' => $company->id,
                'position_title' => 'Staff Magang',
                'employment_type' => EmploymentType::Internship,
                'work_location' => 'Bekasi',
                'message' => 'Mencari kandidat untuk program magang.',
                'status' => RequestStatus::Pending,
            ]);

            foreach ($candidates as $i => $student) {
                $contactRequest->items()->create([
                    'student_id' => $student->id,
                    'status' => $i === 0 ? ItemStatus::Approved : ItemStatus::Pending,
                    'decided_at' => $i === 0 ? now() : null,
                ]);
            }
        });
    }
}
