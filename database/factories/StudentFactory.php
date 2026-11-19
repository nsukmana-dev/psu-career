<?php

namespace Database\Factories;

use App\Enums\Gender;
use App\Enums\JobInterest;
use App\Enums\VerificationStatus;
use App\Models\Student;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Student>
 */
class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->student(),
            'nim' => fake()->unique()->numerify('2#########'),
            'study_program_id' => fn () => StudyProgram::query()->inRandomOrder()->value('id')
                ?? StudyProgram::factory()->create()->id,
            'entry_year' => fake()->numberBetween(2020, 2025),
            'current_semester' => fake()->numberBetween(1, 10),
            'gpa' => fake()->randomFloat(2, 2.5, 4.0),
            'gender' => fake()->randomElement(Gender::cases()),
            'birth_date' => fake()->dateTimeBetween('-26 years', '-18 years'),
            'city' => fake()->city(),
            'phone' => fake()->numerify('08##########'),
            'linkedin_url' => fake()->boolean(60) ? 'https://linkedin.com/in/'.fake()->userName() : null,
            'portfolio_url' => fake()->boolean(30) ? fake()->url() : null,
            'summary' => fake()->boolean(70) ? fake()->paragraph(3) : null,
            'job_interest' => fake()->randomElement(JobInterest::cases()),
            'willing_to_relocate' => fake()->boolean(50),
            'open_to_contact' => fake()->boolean(70),
            'verification_status' => VerificationStatus::Draft,
            'profile_completion' => fake()->numberBetween(20, 100),
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => VerificationStatus::Verified,
            'verified_at' => now(),
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => VerificationStatus::Pending,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'verification_status' => VerificationStatus::Rejected,
            'rejection_reason' => fake()->sentence(),
        ]);
    }
}
