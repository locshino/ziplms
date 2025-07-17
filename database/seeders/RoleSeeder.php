<?php

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles from RoleEnum
        foreach (RoleEnum::cases() as $role) {
            Role::findOrCreate($role->value, 'web');
        }
        $this->command->info('Roles created successfully!');
    }
}
