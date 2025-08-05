<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        $superAdmin = User::firstOrCreate(
            ['email' => 'superadmin@ziplms.com'],
            [
                'name' => 'Super Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $superAdmin->assignRole('super_admin');

        // Create Admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@ziplms.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $admin->assignRole('admin');

        // Create Sample Teacher
        $teacher = User::firstOrCreate(
            ['email' => 'teacher@ziplms.com'],
            [
                'name' => 'John Teacher',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $teacher->assignRole('teacher');

        // Create Sample Student
        $student = User::firstOrCreate(
            ['email' => 'student@ziplms.com'],
            [
                'name' => 'Jane Student',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );
        $student->assignRole('student');

        // Create additional random users
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('student');
        });

        User::factory(3)->create()->each(function ($user) {
            $user->assignRole('teacher');
        });
    }
}
