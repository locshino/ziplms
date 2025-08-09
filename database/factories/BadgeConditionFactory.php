<?php

namespace Database\Factories;

use App\Models\BadgeCondition;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BadgeCondition>
 */
class BadgeConditionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BadgeCondition::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['course_completion', 'quiz_score', 'assignment_grade', 'enrollment_count']);
        $operator = $this->faker->randomElement(['>=', '>', '=', '<', '<=']);

        $parameters = match ($type) {
            'course_completion' => [
                'course_count' => $this->faker->numberBetween(1, 10),
            ],
            'quiz_score' => [
                'min_score' => $this->faker->numberBetween(70, 100),
                'quiz_count' => $this->faker->numberBetween(1, 5),
            ],
            'assignment_grade' => [
                'min_grade' => $this->faker->numberBetween(80, 100),
                'assignment_count' => $this->faker->numberBetween(1, 5),
            ],
            'enrollment_count' => [
                'min_enrollments' => $this->faker->numberBetween(5, 50),
            ],
            default => [],
        };

        return [
            'name' => $this->faker->words(3, true),
            'type' => $type,
            'operator' => $operator,
            'parameters' => $parameters,
        ];
    }

    /**
     * Create a course completion condition.
     */
    public function courseCompletion(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'course_completion',
            'parameters' => [
                'course_count' => $this->faker->numberBetween(1, 10),
            ],
        ]);
    }

    /**
     * Create a quiz score condition.
     */
    public function quizScore(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'quiz_score',
            'parameters' => [
                'min_score' => $this->faker->numberBetween(70, 100),
                'quiz_count' => $this->faker->numberBetween(1, 5),
            ],
        ]);
    }

    /**
     * Create an assignment grade condition.
     */
    public function assignmentGrade(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'assignment_grade',
            'parameters' => [
                'min_grade' => $this->faker->numberBetween(80, 100),
                'assignment_count' => $this->faker->numberBetween(1, 5),
            ],
        ]);
    }
}
