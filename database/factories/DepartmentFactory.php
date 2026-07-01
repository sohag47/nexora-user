<?php

namespace Database\Factories;

use App\Enums\EntityStatus;
use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => ucfirst(fake()->unique()->word()) . ' Department',
            'code' => fake()->unique()->bothify('DEP-####'),
            'branch_id' => Branch::factory(),
            'status' => fake()->randomElement([EntityStatus::DRAFT, EntityStatus::ACTIVE]),
        ];
    }
}
