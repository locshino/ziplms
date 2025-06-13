<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User; // Assuming App\Models\Notification for notification_id
use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserNotification>
 */
class UserNotificationFactory extends Factory
{
    protected $model = UserNotification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'notification_id' => Notification::factory(), // Or SystemNotification::factory() if that's the one
            'read_at' => fake()->optional(0.3)->dateTime,
        ];
    }
}
