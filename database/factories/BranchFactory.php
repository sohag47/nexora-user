<?php

namespace Database\Factories;

use App\Enums\EntityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Branch>
 */
class BranchFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->city() . ' Branch',
            'code' => fake()->unique()->bothify('BR-####'),
            'address' => fake()->address(),
            'status' => fake()->randomElement([EntityStatus::DRAFT, EntityStatus::ACTIVE]),
        ];
    }
}
