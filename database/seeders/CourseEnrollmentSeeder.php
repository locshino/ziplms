<?php

namespace Database\Seeders;

use App\Models\CourseEnrollment;
use Illuminate\Database\Seeder;

class CourseEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CourseEnrollment::factory()->count(30)->create();
    }
}
