<?php

namespace Database\Seeders;

use App\Models\ClassesMajor;
use Illuminate\Database\Seeder;

class ClassesMajorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClassesMajor::factory()->count(10)->create();
    }
}
