<?php

namespace Database\Factories;

use App\Models\QuestionChoice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionChoice>
 */
class QuestionChoiceFactory extends Factory
{
    use Concerns\HasFakesTranslations;

    protected $model = QuestionChoice::class;

    public function definition(): array
    {
        return [
            'choice_text' => $this->fakeWordsTranslations(),
            'is_correct' => false,
            'choice_order' => fake()->randomNumber(1),
        ];
    }
}
