<?php

namespace Database\Seeders;

use App\Models\ExamAnswer;
use Illuminate\Database\Seeder;

class ExamAnswerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExamAnswer::factory()->count(50)->create();
    }
}
