<?php

namespace Database\Factories;

use App\Models\QuestionChoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionChoice>
 */
class QuestionChoiceFactory extends Factory
{
    protected $model = QuestionChoice::class;

    public function definition(): array
    {
        $text = fake()->words(4, true);

        return [
            'choice_text' => ['vi' => $text, 'en' => $text],
            'is_correct' => false,
            'choice_order' => fake()->randomNumber(1),
        ];
    }
}
