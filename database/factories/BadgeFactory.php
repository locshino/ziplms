<?php

namespace Database\Factories;

use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Badge::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'award_status' => $this->faker->randomElement(['automatic', 'manual', 'conditional']),
        ];
    }

    /**
     * Indicate that the badge is automatically awarded.
     */
    public function automatic(): static
    {
        return $this->state(fn (array $attributes) => [
            'award_status' => 'automatic',
        ]);
    }

    /**
     * Indicate that the badge is manually awarded.
     */
    public function manual(): static
    {
        return $this->state(fn (array $attributes) => [
            'award_status' => 'manual',
        ]);
    }

    /**
     * Indicate that the badge is conditionally awarded.
     */
    public function conditional(): static
    {
        return $this->state(fn (array $attributes) => [
            'award_status' => 'conditional',
        ]);
    }
}
