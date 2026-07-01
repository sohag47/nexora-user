<?php

namespace Database\Factories;

use App\Enums\EntityStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->unique()->phoneNumber(),
            // Leaving this null by default since it's nullable. 
            // Alternatively, use: fake()->imageUrl(200, 200, 'people')
            'photo' => null,
            'address' => fake()->address(),
            'status' => fake()->randomElement([EntityStatus::DRAFT, EntityStatus::ACTIVE]),
        ];
    }
}
