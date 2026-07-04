<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Image>
 */
class ImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'url' => 'images/'.fake()->uuid().'.jpg',
            'imageable_id' => Course::factory(),
            'imageable_type' => Course::class,
            'created_by' => 1,
        ];
    }
}
