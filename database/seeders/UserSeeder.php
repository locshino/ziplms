<?php

namespace Database\Seeders;

use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;

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
            // Tạo các tài khoản mặc định từ danh sách
            foreach ($this->getDefaultUsers() as $userInfo) {
                $this->createDefaultUser($userInfo['name'], $userInfo['email'], $userInfo['role']);
            }

            // Xóa các dòng tạo tài khoản mặc định lặp lại
            // Create Admin (1 default user)
            // $this->createDefaultUser('Administrator', 'admin@example.com', RoleSystem::ADMIN->value);

            // Create Manager (1 default user + 9 additional users)
            // $this->createDefaultUser('Default Manager', 'manager@example.com', RoleSystem::MANAGER->value);
            $managers = User::factory(9)->create([
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            foreach ($managers as $manager) {
                $manager->assignRole(RoleSystem::MANAGER->value);
            }

            // Create Teacher (1 default user + 29 additional users)
            // $this->createDefaultUser('Default Teacher', 'teacher@example.com', RoleSystem::TEACHER->value);
            $teachers = User::factory(29)->create([
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
            ]);
            foreach ($teachers as $teacher) {
                $teacher->assignRole(RoleSystem::TEACHER->value);
            }

            // Create Student (1 default user + 599 additional users)
            // $this->createDefaultUser('Default Student', 'student@example.com', RoleSystem::STUDENT->value);
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
     * Create default user for a role.
     */
    private function createDefaultUser(string $name, string $email, string $role): void
    {
        $user = User::factory()->create([
            'name' => $name,
            'email' => $email,
            'email_verified_at' => now(),
            'status' => UserStatus::ACTIVE->value,
        ]);
        $user->assignRole($role);
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

    /**
     * Danh sách tài khoản mặc định cho từng role.
     */
    private function getDefaultUsers(): array
    {
        return [
            [
                'name' => 'Super Administrator',
                'email' => 'superadmin@example.com',
                'role' => RoleSystem::SUPER_ADMIN->value,
            ],
            [
                'name' => 'Administrator',
                'email' => 'admin@example.com',
                'role' => RoleSystem::ADMIN->value,
            ],
            [
                'name' => 'Default Manager',
                'email' => 'manager@example.com',
                'role' => RoleSystem::MANAGER->value,
            ],
            [
                'name' => 'Default Teacher',
                'email' => 'teacher@example.com',
                'role' => RoleSystem::TEACHER->value,
            ],
            [
                'name' => 'Default Student',
                'email' => 'student@example.com',
                'role' => RoleSystem::STUDENT->value,
            ],
        ];
    }
}
