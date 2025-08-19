<?php

namespace Database\Factories;

use App\Enums\Status\AssignmentStatus;
use App\Models\Assignment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'max_points' => $this->faker->randomFloat(2, 10, 100),
            'max_attempts' => $this->faker->numberBetween(1, 5),
            'status' => AssignmentStatus::PUBLISHED->value,
        ];
    }

    /**
     * Indicate that the assignment has a specific status.
     */
    public function withStatus(AssignmentStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status->value,
        ]);
    }

    /**
     * Indicate that the assignment is a draft.
     */
    public function draft(): static
    {
        return $this->withStatus(AssignmentStatus::DRAFT);
    }

    /**
     * Indicate that the assignment is published.
     */
    public function published(): static
    {
        return $this->withStatus(AssignmentStatus::PUBLISHED);
    }
}