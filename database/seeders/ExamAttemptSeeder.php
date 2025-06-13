<?php

namespace Database\Seeders;

use App\Models\ExamAttempt;
use Illuminate\Database\Seeder;

class ExamAttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExamAttempt::factory()->count(20)->create();
    }
}
