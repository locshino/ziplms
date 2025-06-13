<?php

namespace Database\Seeders;

use App\Models\QuestionChoice;
use Illuminate\Database\Seeder;

class QuestionChoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // QuestionChoices are usually created along with Questions.
        // If you need to seed them independently:
        // QuestionChoice::factory()->count(50)->create();
        // However, it's better to let QuestionFactory handle its choices.
        $this->command->info('QuestionChoiceSeeder is typically handled by QuestionSeeder.');
    }
}
