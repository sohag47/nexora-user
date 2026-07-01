<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'body' => fake()->sentence(),
            'commentable_id' => Course::factory(), // Default Course এর জন্য
            'commentable_type' => Course::class,
        ];
    }

    public function forStudent()
    {
        return $this->state(fn() => [
            'commentable_id' => Student::factory(),
            'commentable_type' => Student::class,
        ]);
    }
}
