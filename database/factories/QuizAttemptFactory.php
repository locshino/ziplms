<?php

namespace Database\Factories;

use App\Enums\Status\QuizAttemptStatus;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAttempt>
 */
class QuizAttemptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = $this->faker->dateTimeBetween('-2 months', 'now');
        $endAt = $this->faker->optional(0.8)->dateTimeBetween($startAt, 'now'); // 80% chance to have an end_at

        $status = QuizAttemptStatus::STARTED;
        if ($endAt) {
            $status = $this->faker->randomElement([QuizAttemptStatus::COMPLETED, QuizAttemptStatus::GRADED]);
        }

        $points = null;
        if ($status === QuizAttemptStatus::GRADED) {
            $points = $this->faker->randomFloat(2, 0, 100);
        }

        return [
            'quiz_id' => Quiz::factory(),
            'student_id' => User::factory(),
            'points' => $points,
            'answers' => null, // You might want to generate some JSON answers here
            'start_at' => $startAt,
            'end_at' => $endAt,
            'status' => $status->value,
        ];
    }

    /**
     * Indicate that the quiz attempt has a specific status.
     */
    public function withStatus(QuizAttemptStatus $status): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => $status->value,
        ]);
    }

    /**
     * Indicate that the quiz attempt is completed.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $endAt = $this->faker->dateTimeBetween($attributes['start_at'], 'now');

            return [
                'status' => QuizAttemptStatus::COMPLETED->value,
                'end_at' => $endAt,
            ];
        });
    }

    /**
     * Indicate that the quiz attempt is graded.
     */
    public function graded(): static
    {
        return $this->state(function (array $attributes) {
            $endAt = $this->faker->dateTimeBetween($attributes['start_at'], 'now');

            return [
                'status' => QuizAttemptStatus::GRADED->value,
                'end_at' => $endAt,
                'points' => $this->faker->randomFloat(2, 0, 100),
            ];
        });
    }

    /**
     * Indicate that the quiz attempt is in progress.
     */
    public function inProgress(): static
    {
        return $this->withStatus(QuizAttemptStatus::IN_PROGRESS);
    }

    /**
     * Indicate that the quiz attempt is abandoned.
     */
    public function abandoned(): static
    {
        return $this->withStatus(QuizAttemptStatus::ABANDONED);
    }
}
