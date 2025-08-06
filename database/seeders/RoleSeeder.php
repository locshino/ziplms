<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'super_admin',
                'guard_name' => 'web',
                'is_system' => true,
            ],
            [
                'name' => 'admin',
                'guard_name' => 'web',
                'is_system' => true,
            ],
            [
                'name' => 'teacher',
                'guard_name' => 'web',
                'is_system' => true,
            ],
            [
                'name' => 'student',
                'guard_name' => 'web',
                'is_system' => true,
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name'], 'guard_name' => $roleData['guard_name']],
                $roleData
            );
        }
    }
}
