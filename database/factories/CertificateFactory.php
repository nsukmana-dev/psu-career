<?php

namespace Database\Factories;

use App\Models\Certificate;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Certificate>
 */
class CertificateFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'name' => fake()->words(3, true),
            'issuer' => fake()->company(),
            'issued_at' => fake()->dateTimeBetween('-2 years', 'now'),
        ];
    }
}
