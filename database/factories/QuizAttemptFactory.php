<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizAttempt>
 */
class QuizAttemptFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = QuizAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startedAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $isCompleted = $this->faker->boolean(80);
        $completedAt = $isCompleted ? $this->faker->dateTimeBetween($startedAt, 'now') : null;
        $status = $isCompleted ? $this->faker->randomElement(['completed', 'submitted']) : $this->faker->randomElement(['in_progress', 'paused']);

        return [
            'quiz_id' => Quiz::factory(),
            'student_id' => User::factory(),
            'attempt_number' => $this->faker->numberBetween(1, 3),
            'score' => $isCompleted ? $this->faker->randomFloat(2, 0, 100) : null,
            'status' => $status,
            'started_at' => $startedAt,
            'completed_at' => $completedAt,
        ];
    }

    /**
     * Indicate that the quiz attempt is completed.
     */
    public function completed(): static
    {
        return $this->state(function (array $attributes) {
            $completedAt = $this->faker->dateTimeBetween($attributes['started_at'], 'now');
            
            return [
                'score' => $this->faker->randomFloat(2, 0, 100),
                'status' => 'completed',
                'completed_at' => $completedAt,
            ];
        });
    }

    /**
     * Indicate that the quiz attempt is in progress.
     */
    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => null,
            'status' => 'in_progress',
            'completed_at' => null,
        ]);
    }

    /**
     * Indicate that the quiz attempt is submitted but not graded.
     */
    public function submitted(): static
    {
        return $this->state(function (array $attributes) {
            $completedAt = $this->faker->dateTimeBetween($attributes['started_at'], 'now');
            
            return [
                'score' => null,
                'status' => 'submitted',
                'completed_at' => $completedAt,
            ];
        });
    }
}