<?php

namespace Database\Seeders;

use App\Models\CourseStaffAssignment;
use Illuminate\Database\Seeder;

class CourseStaffAssignmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseStaffAssignment::factory()->count(10)->create();
    }
}
