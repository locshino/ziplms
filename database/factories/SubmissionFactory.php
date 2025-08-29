<?php

namespace Database\Factories;

use App\Enums\Status\SubmissionStatus;
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
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $submittedAt = $this->faker->optional(0.9)->dateTimeBetween('-2 months', 'now'); // 90% chance to have a submission date
        $status = SubmissionStatus::DRAFT;
        if ($submittedAt) {
            $status = $this->faker->randomElement([SubmissionStatus::SUBMITTED, SubmissionStatus::GRADED, SubmissionStatus::LATE]);
        }

        return [
            'assignment_id' => Assignment::factory(),
            'student_id' => User::factory(),
            'content' => $this->faker->optional()->paragraph(4),
            'submitted_at' => $submittedAt,
            'status' => $status->value,
            'points' => null,
            'graded_by' => null,
            'graded_at' => null,
            'feedback' => null,
        ];
    }

    /**
     * Indicate that the submission is graded.
     */
    public function graded(): static
    {
        return $this->state(function (array $attributes) {
            $gradedAt = $this->faker->dateTimeBetween($attributes['submitted_at'], 'now');

            return [
                'points' => $this->faker->randomFloat(2, 0, 100),
                'graded_by' => User::factory(),
                'graded_at' => $gradedAt,
                'feedback' => $this->faker->paragraph(2),
            ];
        });
    }

    /**
     * Indicate that the submission was submitted late.
     */
    public function late(): static
    {
        return $this->state(fn(array $attributes) => [
            'submitted_at' => $this->faker->dateTimeBetween('now', '+1 week'),
        ]);
    }

    /**
     * Indicate that the submission was submitted on time.
     */
    public function onTime(): static
    {
        return $this->state(fn(array $attributes) => [
            'submitted_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }
}
