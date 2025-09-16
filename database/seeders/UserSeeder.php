<?php

namespace Database\Seeders;

use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles first
        $this->createRoles();

        // Create a default user for each role for easy testing
        foreach ($this->getDefaultUsers() as $userInfo) {
            $this->createDefaultUser($userInfo['name'], $userInfo['email'], $userInfo['role']);
        }

        // Create additional random users for each role
        $this->createRandomUsers(RoleSystem::MANAGER, 9);
        $this->createRandomUsers(RoleSystem::TEACHER, 29);
        $this->createRandomUsers(RoleSystem::STUDENT, 599);
    }

    /**
     * Create default user for a role.
     */
    private function createDefaultUser(string $name, string $email, string $role): void
    {
        // Use updateOrCreate to avoid duplication issues on re-seeding
        $user = User::updateOrCreate(
            ['email' => $email],
            [
                'name' => $name,
                'email_verified_at' => now(),
                'status' => UserStatus::ACTIVE->value,
                'password' => bcrypt('password'), // Set a default password
            ]
        );
        $user->assignRole($role);
    }

    /**
     * Create a specified number of random users for a given role.
     */
    private function createRandomUsers(RoleSystem $role, int $count): void
    {
        $users = User::factory($count)->create([
            'email_verified_at' => now(),
            'status' => UserStatus::ACTIVE->value,
        ]);

        // Use mass assignment for better performance
        foreach ($users as $user) {
            $user->assignRole($role->value);
        }
    }

    /**
     * Create roles if they don't exist.
     */
    private function createRoles(): void
    {
        foreach (RoleSystem::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value]);
        }
    }

    /**
     * Get the list of default accounts for each role.
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
