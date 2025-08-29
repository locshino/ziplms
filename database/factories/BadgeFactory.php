<?php

namespace Database\Factories;

use App\Enums\Status\BadgeStatus;
use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);

        return [
            'title' => Str::title($title),
            'slug' => Str::slug($title),
            'description' => $this->faker->sentence(10),
            'status' => $this->faker->randomElement(BadgeStatus::cases())->value,
        ];
    }

    /**
     * Indicate that the badge has a specific status.
     */
    public function withStatus(BadgeStatus $status): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => $status->value,
        ]);
    }

    /**
     * Indicate that the badge is active.
     */
    public function active(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => BadgeStatus::ACTIVE->value,
        ]);
    }

    /**
     * Indicate that the badge is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'status' => BadgeStatus::INACTIVE->value,
        ]);
    }

    /**
     * Indicate that the badge is archived.
     */
    public function archived(): static
    {
        return $this->withStatus(BadgeStatus::ARCHIVED);
    }
}
