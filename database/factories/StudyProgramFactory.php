<?php

namespace Database\Factories;

use App\Enums\StudyProgramLevel;
use App\Models\StudyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudyProgram>
 */
class StudyProgramFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => fake()->unique()->lexify('???').fake()->unique()->numberBetween(1, 999),
            'name' => fake()->unique()->words(3, true),
            'level' => fake()->randomElement(StudyProgramLevel::cases()),
            'is_active' => true,
        ];
    }
}
