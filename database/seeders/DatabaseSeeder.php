<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
            AdditionalDataSeeder::class,
        ]);
    }
}
