<?php

namespace Database\Factories;

use App\Enums\EntityStatus;
use App\Models\Branch;
use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
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
            'name' => ucfirst(fake()->unique()->word()).' Department',
            'code' => fake()->unique()->bothify('DEP-####'),
            'branch_id' => Branch::factory(),
            'status' => fake()->randomElement([EntityStatus::DRAFT, EntityStatus::ACTIVE]),
            'created_by' => 1,
        ];
    }
}
