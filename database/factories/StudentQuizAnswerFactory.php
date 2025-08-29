<?php

namespace Database\Factories;

use App\Models\AnswerChoice;
use App\Models\Question;
use App\Models\QuizAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StudentQuizAnswer>
 */
class StudentQuizAnswerFactory extends Factory
{
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

    /**
     * Indicate that the answer is correct.
     */
    public function correct(): static
    {
        return $this->state(function (array $attributes) {
            // Get a correct answer choice for the question
            $question = Question::find($attributes['question_id']) ?? Question::factory()->create();
            $correctChoice = $question->answerChoices()->where('is_correct', true)->first();

            if (! $correctChoice) {
                $correctChoice = AnswerChoice::factory()->correct()->create([
                    'question_id' => $question->id,
                ]);
            }

            return [
                'question_id' => $question->id,
                'answer_choice_id' => $correctChoice->id,
            ];
        });
    }

    /**
     * Indicate that the answer is incorrect.
     */
    public function incorrect(): static
    {
        return $this->state(function (array $attributes) {
            // Get an incorrect answer choice for the question
            $question = Question::find($attributes['question_id']) ?? Question::factory()->create();
            $incorrectChoice = $question->answerChoices()->where('is_correct', false)->first();

            if (! $incorrectChoice) {
                $incorrectChoice = AnswerChoice::factory()->incorrect()->create([
                    'question_id' => $question->id,
                ]);
            }

            return [
                'question_id' => $question->id,
                'answer_choice_id' => $incorrectChoice->id,
            ];
        });
    }
}
