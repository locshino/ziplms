<?php

namespace Database\Seeders;

use App\Models\UserClassMajorEnrollment;
use Illuminate\Database\Seeder;

class UserClassMajorEnrollmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserClassMajorEnrollment::factory()->count(20)->create();
    }
}
