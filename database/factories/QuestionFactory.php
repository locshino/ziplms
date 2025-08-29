<?php

namespace Database\Factories;

use App\Enums\Status\QuestionStatus;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->unique()->sentence(8) . '?',
            'description' => $this->faker->optional()->paragraph(2),
            'status' => $this->faker->randomElement(QuestionStatus::cases())->value,
        ];
    }

    /**
     * Indicate that the question has a specific status.
     */
    public function withStatus(QuestionStatus $status): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => $status->value,
        ]);
    }

    /**
     * Indicate that the question is a multiple choice question.
     */
    public function multipleChoice(): static
    {
        return $this->state(fn(array $attributes) => [
            'is_multi_choice' => true,
        ]);
    }

    /**
     * Indicate that the question is a true/false question.
     */
    public function trueFalse(): static
    {
        return $this->state(fn(array $attributes) => [
            'question_type' => 'true_false',
        ]);
    }

    /**
     * Indicate that the question is a short answer question.
     */
    public function shortAnswer(): static
    {
        return $this->state(fn(array $attributes) => [
            'question_type' => 'short_answer',
        ]);
    }
}
