<?php

namespace Database\Seeders;

use App\Models\ExamQuestion;
use Illuminate\Database\Seeder;

class ExamQuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExamQuestion::factory()->count(30)->create();
    }
}
