<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrganizationUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\OrganizationUser::factory()->count(10)->create();
    }
}
