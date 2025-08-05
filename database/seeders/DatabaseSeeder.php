<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in proper order to handle dependencies
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            UserSeeder::class,
            CourseSeeder::class,
            EnrollmentSeeder::class,
            AssignmentSeeder::class,
            QuizSeeder::class,
            BadgeSeeder::class,
        ]);
    }
}
