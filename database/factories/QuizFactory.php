<?php

namespace Database\Factories;

use App\Enums\Status\QuizStatus;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'max_attempts' => $this->faker->numberBetween(1, 3),
            'is_single_session' => $this->faker->boolean(70), // 70% chance of single session
            'time_limit_minutes' => $this->faker->randomElement([30, 45, 60, 90, 120]),
            'status' => QuizStatus::PUBLISHED->value,
        ];
    }

    /**
     * Indicate that the quiz has a specific status.
     */
    public function withStatus(QuizStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status->value,
        ]);
    }

    /**
     * Indicate that the quiz is a draft.
     */
    public function draft(): static
    {
        return $this->withStatus(QuizStatus::DRAFT);
    }

    /**
     * Indicate that the quiz is published.
     */
    public function published(): static
    {
        return $this->withStatus(QuizStatus::PUBLISHED);
    }

    /**
     * Indicate that the quiz is closed.
     */
    public function closed(): static
    {
        return $this->withStatus(QuizStatus::CLOSED);
    }

    /**
     * Indicate that the quiz allows multiple sessions.
     */
    public function multipleSession(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_single_session' => false,
        ]);
    }
}
