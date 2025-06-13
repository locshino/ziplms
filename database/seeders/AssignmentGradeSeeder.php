<?php

namespace Database\Seeders;

use App\Models\AssignmentGrade;
use Illuminate\Database\Seeder;

class AssignmentGradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssignmentGrade::factory()->count(10)->create();
    }
}
