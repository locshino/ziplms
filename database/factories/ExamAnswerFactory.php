<?php

namespace Database\Factories;

use App\Models\ExamAnswer;
use App\Models\ExamAttempt;
use App\Models\ExamQuestion;
use App\Models\Question;
use App\Models\QuestionChoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAnswer>
 */
class ExamAnswerFactory extends Factory
{
    protected $model = ExamAnswer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Note: Logic for chosen_option_ids and selected_choice_id might need adjustment
        // based on the actual question_type of the associated Question.
        // This is a generic setup.
        return [
            'exam_attempt_id' => ExamAttempt::factory(),
            'exam_question_id' => ExamQuestion::factory(),
            'question_id' => Question::factory(), // Should ideally match exam_question's question
            'answer_text' => fake()->optional()->paragraph,
            'chosen_option_ids' => null, // Example: json_encode([QuestionChoice::factory()->create()->id])
            'selected_choice_id' => null, // Example: QuestionChoice::factory()
            'points_earned' => fake()->optional()->randomFloat(2, 0, 1),
            'is_correct' => fake()->optional()->boolean,
            'teacher_feedback' => ['vi' => fake()->optional()->sentence, 'en' => fake()->optional()->sentence],
            'graded_at' => fake()->optional()->dateTime,
            'graded_by' => fake()->optional(0.5, null)->passthrough(User::factory()),
        ];
    }
}
