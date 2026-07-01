<?php

namespace Database\Factories;

use App\Enums\EntityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->jobTitle(),
            'order' => (string) fake()->unique()->numberBetween(1, 100),
            'status' => fake()->randomElement([EntityStatus::DRAFT, EntityStatus::ACTIVE]),
        ];
    }
}
