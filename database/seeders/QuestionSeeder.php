<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Question::factory()->count(20)->create()->each(function ($question) {
            if (str_starts_with($question->question_type, 'mcq')) {
                \App\Models\QuestionChoice::factory()->count(4)->create(['question_id' => $question->id]);
                // Ensure at least one is correct
                $question->choices()->inRandomOrder()->first()?->update(['is_correct' => true]);
            }
        });
    }
}
