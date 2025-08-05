<?php

namespace Database\Factories;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserBadge>
 */
class UserBadgeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserBadge::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'badge_id' => Badge::factory(),
            'awarded_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Indicate that the badge was recently awarded.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'awarded_at' => $this->faker->dateTimeBetween('-1 week', 'now'),
        ]);
    }

    /**
     * Indicate that the badge was awarded long ago.
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'awarded_at' => $this->faker->dateTimeBetween('-2 years', '-6 months'),
        ]);
    }
}