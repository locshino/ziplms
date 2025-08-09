<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teachers = User::role('teacher')->get();

        if ($teachers->isEmpty()) {
            $this->command->warn('No teachers found. Please run UserSeeder first.');

            return;
        }

        $courses = [
            [
                'title' => 'Introduction to Programming',
                'description' => 'Learn the fundamentals of programming using modern languages and best practices.',
            ],
            [
                'title' => 'Web Development Basics',
                'description' => 'Master HTML, CSS, and JavaScript to build modern web applications.',
            ],
            [
                'title' => 'Database Design and Management',
                'description' => 'Understand relational databases, SQL, and database optimization techniques.',
            ],
            [
                'title' => 'Mobile App Development',
                'description' => 'Create mobile applications for iOS and Android platforms.',
            ],
            [
                'title' => 'Data Science Fundamentals',
                'description' => 'Introduction to data analysis, statistics, and machine learning concepts.',
            ],
        ];

        foreach ($courses as $courseData) {
            Course::create(array_merge($courseData, [
                'teacher_id' => $teachers->random()->id,
            ]));
        }

        // Create additional random courses
        Course::factory(10)->create();
    }
}
