<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Course;
use App\Models\Professor;
use App\Models\Student;
use App\Models\StudentProfile;
use App\Models\University;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Image;

class UniversitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        University::factory(3)
            ->has(
                Professor::factory(4)
                    ->has(
                        Course::factory(3)
                            ->has(Image::factory())
                            ->has(Comment::factory(5))
                    )
            )
            ->create();


        $courses = Course::all();

        Student::factory(20)
            ->hasProfile(StudentProfile::factory()) // Relation method এর নাম: profile
            ->create()
            ->each(function ($student) use ($courses) {
                // N:M Pivot: Random 2-5 টা Course এ Enroll করাও + Grade দাও
                $student->courses()->attach(
                    $courses->random(rand(2, 5))->pluck('id')->toArray(),
                    ['grade' => fake()->randomElement(['A+', 'A', 'B', 'C'])]
                );
                Comment::factory(2)->forStudent()->create(['commentable_id' => $student->id]);
            });
    }

    // public function run(): void
    // {
    //     $uni = University::create(['name' => 'DU']);
    //     $prof = Professor::create(['name' => 'Mr. Karim', 'university_id' => $uni->id]);
    //     $course = Course::create(['title' => 'Laravel 11', 'professor_id' => $prof->id]);
    //     $student = Student::create(['name' => 'Rahim']);
    //     StudentProfile::create(['student_id' => $student->id, 'bio' => 'Laravel Dev']);

    //     $student->courses()->attach($course->id, ['grade' => 'A+']); // N:M with pivot

    //     $course->comments()->create(['body' => 'Best Course']); // Polymorphic N:1
    //     $student->comments()->create(['body' => 'Good Student']); // Polymorphic N:1
    //     $course->image()->create(['url' => 'laravel.jpg']); // Polymorphic 1:1
    // }
}
