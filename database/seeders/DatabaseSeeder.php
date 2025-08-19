<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CourseSeeder::class,
            AssignmentSeeder::class,
            QuizSeeder::class,
            BadgeSeeder::class,
            AdditionalDataSeeder::class,
        ]);
    }

    /**
     * Clear all seeder cache data.
     * Use this when you want to force regenerate all seeder data.
     */
    public function clearCache(): void
    {
        $cacheKeys = [
            '_seeder_faker_users',
            '_seeder_faker_roles',
            '_seeder_faker_courses',
            '_seeder_faker_tags',
            '_seeder_faker_assignments',
            '_seeder_faker_quizzes',
            '_seeder_faker_badges',
            '_seeder_faker_badge_conditions',
        ];

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }

        $this->command->info('All seeder cache cleared successfully!');
    }
}
