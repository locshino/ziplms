<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Submission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Submission>
 */
class SubmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Submission::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $submittedAt = $this->faker->dateTimeBetween('-1 month', 'now');
        $isGraded = $this->faker->boolean(70);
        
        return [
            'assignment_id' => Assignment::factory(),
            'student_id' => User::factory(),
            'grade' => $isGraded ? $this->faker->randomFloat(2, 0, 100) : null,
            'feedback' => $isGraded ? $this->faker->optional(0.8)->paragraph() : null,
            'submitted_at' => $submittedAt,
            'graded_by' => $isGraded ? User::factory() : null,
            'graded_at' => $isGraded ? $this->faker->dateTimeBetween($submittedAt, 'now') : null,
            'version' => $this->faker->numberBetween(1, 3),
        ];
    }

    /**
     * Indicate that the submission is ungraded.
     */
    public function ungraded(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => null,
            'feedback' => null,
            'graded_by' => null,
            'graded_at' => null,
        ]);
    }

    /**
     * Indicate that the submission is graded.
     */
    public function graded(): static
    {
        return $this->state(fn (array $attributes) => [
            'grade' => $this->faker->randomFloat(2, 0, 100),
            'feedback' => $this->faker->paragraph(),
            'graded_by' => User::factory(),
            'graded_at' => $this->faker->dateTimeBetween($attributes['submitted_at'], 'now'),
        ]);
    }
}