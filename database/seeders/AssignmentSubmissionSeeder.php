<?php

namespace Database\Seeders;

use App\Models\AssignmentSubmission;
use Illuminate\Database\Seeder;

class AssignmentSubmissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AssignmentSubmission::factory()->count(20)->create();
    }
}
