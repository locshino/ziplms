<?php

namespace Database\Seeders;

use App\Models\LectureMaterial;
use Illuminate\Database\Seeder;

class LectureMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        LectureMaterial::factory()->count(20)->create();
    }
}
