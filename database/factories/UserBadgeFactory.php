<?php

namespace Database\Factories;

use App\Models\UserBadge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserBadge>
 */
class UserBadgeFactory extends Factory
{
    protected $model = UserBadge::class;

    public function definition(): array
    {
        // Để trống vì user_id và badge_id sẽ được cung cấp bởi Seeder.
        return [];
    }
}
