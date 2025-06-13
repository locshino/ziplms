<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAttempt>
 */
class ExamAttemptFactory extends Factory
{
    protected $model = ExamAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'exam_id' => Exam::factory(),
            'user_id' => User::factory(),
            'attempt_number' => 1,
            'started_at' => now(),
            'completed_at' => fake()->optional()->dateTimeInInterval(now(), '+2 hours'),
            'score' => fake()->optional()->randomFloat(2, 0, 100),
            'time_spent_seconds' => fake()->optional()->numberBetween(600, 7200),
            'feedback' => ['vi' => fake()->optional()->sentence, 'en' => fake()->optional()->sentence],
        ];
    }
}
