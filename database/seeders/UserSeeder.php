<?php

namespace Database\Seeders;

use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use App\Models\User;
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;
use App\Models\Role;

class UserSeeder extends Seeder
{
    use HasCacheSeeder;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles first (always check, not cached separately)
        $this->createRoles();

        // Skip if users already exist and cache is valid
        if ($this->shouldSkipSeeding('users', 'users')) {
            return;
        }

        // Get or create users with caching
        $this->getCachedData('users', function () {
            // Create Super Admin (1 default user)
            $superAdmin = User::factory()->create([
                'name' => 'Super Administrator',
                'email' => 'superadmin@example.com',
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            $superAdmin->assignRole(RoleSystem::SUPER_ADMIN->value);

            // Create Admin (1 default user)
            $admin = User::factory()->create([
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            $admin->assignRole(RoleSystem::ADMIN->value);

            // Create Manager (1 default user + 9 additional users)
            $defaultManager = User::factory()->create([
                'name' => 'Default Manager',
                'email' => 'manager@example.com',
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            $defaultManager->assignRole(RoleSystem::MANAGER->value);

            $managers = User::factory(9)->create([
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            foreach ($managers as $manager) {
                $manager->assignRole(RoleSystem::MANAGER->value);
            }

            // Create Teacher (1 default user + 29 additional users)
            $defaultTeacher = User::factory()->create([
                'name' => 'Default Teacher',
                'email' => 'teacher@example.com',
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            $defaultTeacher->assignRole(RoleSystem::TEACHER->value);

            $teachers = User::factory(29)->create([
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            foreach ($teachers as $teacher) {
                $teacher->assignRole(RoleSystem::TEACHER->value);
            }

            // Create Student (1 default user + 599 additional users)
            $defaultStudent = User::factory()->create([
                'name' => 'Default Student',
                'email' => 'student@example.com',
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            $defaultStudent->assignRole(RoleSystem::STUDENT->value);

            $students = User::factory(599)->create([
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            foreach ($students as $student) {
                $student->assignRole(RoleSystem::STUDENT->value);
            }

            return true;
        });
    }

    /**
     * Create roles if they don't exist.
     */
    private function createRoles(): void
    {
        $roles = [
            RoleSystem::SUPER_ADMIN->value,
            RoleSystem::ADMIN->value,
            RoleSystem::MANAGER->value,
            RoleSystem::TEACHER->value,
            RoleSystem::STUDENT->value,
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
