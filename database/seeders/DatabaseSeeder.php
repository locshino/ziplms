<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * The order of seeders is important to ensure data integrity.
     * 1. Users & Permissions: Base authentication and authorization.
     * 2. Core Content: Courses, which are containers for other content.
     * 3. Dependent Content: Assignments and Quizzes, which belong to courses.
     * 4. Gamification/Misc: Badges and other data that may depend on content.
     * 5. Additional Data: A final seeder to link default users to content for easy testing.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            PermissionSeeder::class,
            CourseSeeder::class,
            AssignmentSeeder::class,
            QuizSeeder::class,
            BadgeSeeder::class,
                // This seeder MUST run last to connect all the generated data
            AdditionalDataSeeder::class,
        ]);
    }
}
