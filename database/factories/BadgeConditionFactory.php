<?php

namespace Database\Factories;

use App\Enums\Status\BadgeConditionStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BadgeCondition>
 */
class BadgeConditionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $conditionTypes = [
            'course_completion',
            'quiz_score',
            'assignment_submission',
            'login_streak',
            'points_earned',
        ];

        $conditionType = $this->faker->randomElement($conditionTypes);

        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(2),
            'condition_type' => $conditionType,
            'condition_data' => $this->generateConditionData($conditionType),
            'status' => BadgeConditionStatus::ACTIVE->value,
        ];
    }

    /**
     * Generate condition data based on condition type.
     */
    private function generateConditionData(string $conditionType): array
    {
        return match ($conditionType) {
            'course_completion' => [
                'required_courses' => $this->faker->numberBetween(1, 5),
                'specific_course_ids' => [],
            ],
            'quiz_score' => [
                'minimum_score' => $this->faker->numberBetween(80, 100),
                'quiz_count' => $this->faker->numberBetween(1, 10),
            ],
            'assignment_submission' => [
                'required_submissions' => $this->faker->numberBetween(5, 20),
                'on_time_only' => $this->faker->boolean(70),
            ],
            'login_streak' => [
                'consecutive_days' => $this->faker->numberBetween(7, 30),
            ],
            'points_earned' => [
                'minimum_points' => $this->faker->numberBetween(100, 1000),
                'time_period' => $this->faker->randomElement(['week', 'month', 'all_time']),
            ],
            default => [],
        };
    }

    /**
     * Indicate that the badge condition has a specific status.
     */
    public function withStatus(BadgeConditionStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status->value,
        ]);
    }

    /**
     * Indicate that the badge condition is active.
     */
    public function active(): static
    {
        return $this->withStatus(BadgeConditionStatus::ACTIVE);
    }

    /**
     * Indicate that the badge condition is inactive.
     */
    public function inactive(): static
    {
        return $this->withStatus(BadgeConditionStatus::INACTIVE);
    }
}
