<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $students = User::role('student')->get();
        $courses = Course::all();

        if ($students->isEmpty()) {
            $this->command->warn('No students found. Please run UserSeeder first.');

            return;
        }

        if ($courses->isEmpty()) {
            $this->command->warn('No courses found. Please run CourseSeeder first.');

            return;
        }

        // Enroll each student in 2-5 random courses
        foreach ($students as $student) {
            $coursesToEnroll = $courses->random(rand(2, min(5, $courses->count())));

            foreach ($coursesToEnroll as $course) {
                // Check if enrollment already exists to avoid duplicates
                if (! Enrollment::where('student_id', $student->id)
                    ->where('course_id', $course->id)
                    ->exists()) {
                    Enrollment::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'enrolled_at' => fake()->dateTimeBetween('-6 months', 'now'),
                    ]);
                }
            }
        }

        // Create additional random enrollments
        Enrollment::factory(50)->create();
    }
}
