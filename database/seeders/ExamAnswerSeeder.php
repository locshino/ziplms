<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ExamAnswers are now seeded via ExamAttemptSeeder to ensure proper relationships
        // and context-aware answer generation based on question type.
        $this->command->info('ExamAnswerSeeder logic has been moved to ExamAttemptSeeder and is no longer run directly.');
    }
}
