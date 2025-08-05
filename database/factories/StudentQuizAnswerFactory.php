<?php

namespace Database\Factories;

use App\Models\AnswerChoice;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\StudentQuizAnswer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentQuizAnswer>
 */
class StudentQuizAnswerFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentQuizAnswer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_attempt_id' => QuizAttempt::factory(),
            'question_id' => Question::factory(),
            'answer_choice_id' => AnswerChoice::factory(),
        ];
    }
}
