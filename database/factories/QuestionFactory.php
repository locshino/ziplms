<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    use Concerns\HasFakesTranslations;

    protected $model = Question::class;

    public function definition(): array
    {
        return [
            'question_text' => $this->fakeSentenceTranslations(),
            'explanation' => $this->fakeParagraphTranslations(),
        ];
    }
}
