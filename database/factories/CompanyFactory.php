<?php

namespace Database\Factories;

use App\Enums\CompanyStatus;
use App\Models\Company;
use App\Models\Industry;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Company>
 */
class CompanyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->company(),
            'name' => fake()->company(),
            'industry_id' => fn () => Industry::query()->inRandomOrder()->value('id'),
            'address' => fake()->address(),
            'city' => fake()->city(),
            'website' => fake()->url(),
            'description' => fake()->paragraph(4),
            'pic_name' => fake()->name(),
            'pic_position' => fake()->jobTitle(),
            'pic_phone' => fake()->numerify('08##########'),
            'status' => CompanyStatus::Active,
        ];
    }

    public function suspended(): static
    {
        return $this->state(fn (array $attributes) => ['status' => CompanyStatus::Suspended]);
    }
}
