<?php

namespace Database\Factories;

use App\Models\UserBadge;
use App\Models\User;
use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserBadge>
 */
class UserBadgeFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel;

    protected $model = UserBadge::class;

    public function definition(): array
    {
        // Để trống vì user_id và badge_id sẽ được cung cấp bởi Seeder.
        return [
            'user_id' => $this->assignRandomOrNewModel(User::class),
            'badge_id' => $this->assignRandomOrNewModel(Badge::class),
            'awarded_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
