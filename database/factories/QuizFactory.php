<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Quiz>
 */
class QuizFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = $this->faker->dateTimeBetween('now', '+1 month');
        $endAt = $this->faker->dateTimeBetween($startAt, '+2 months');

        return [
            'course_id' => Course::factory(),
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->optional(0.8)->paragraph(),
            'max_points' => $this->faker->randomFloat(2, 50, 100),
            'max_attempts' => $this->faker->optional(0.6)->numberBetween(1, 5),
            'is_single_session' => $this->faker->boolean(30),
            'time_limit_minutes' => $this->faker->optional(0.7)->numberBetween(15, 120),
            'start_at' => $startAt,
            'end_at' => $endAt,
        ];
    }

    /**
     * Indicate that the quiz has unlimited attempts.
     */
    public function unlimitedAttempts(): static
    {
        return $this->state(fn (array $attributes) => [
            'max_attempts' => null,
        ]);
    }

    /**
     * Indicate that the quiz has no time limit.
     */
    public function noTimeLimit(): static
    {
        return $this->state(fn (array $attributes) => [
            'time_limit_minutes' => null,
        ]);
    }

    /**
     * Indicate that the quiz is a single session quiz.
     */
    public function singleSession(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_single_session' => true,
        ]);
    }
}