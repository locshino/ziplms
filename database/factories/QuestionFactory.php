<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition(): array
    {
        $text = fake()->sentence(12).'?';

        return [
            'question_text' => ['vi' => $text, 'en' => $text],
            'question_type' => fake()->randomElement(['mcq_single', 'mcq_multiple']),
            'explanation' => ['vi' => 'Giải thích: '.fake()->sentence, 'en' => 'Explanation: '.fake()->sentence],
        ];
    }
}
