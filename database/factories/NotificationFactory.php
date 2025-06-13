<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    protected $model = \App\Models\Notification::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    { // Maps to 'notifications' table structure
        $title = fake()->sentence(4);

        return [
            'type' => fake()->word,
            'title' => ['vi' => 'Thông báo: '.$title, 'en' => 'Notification: '.$title],
            'content' => ['vi' => fake()->paragraph, 'en' => fake()->paragraph],
            'link' => fake()->optional()->url,
            'sender_id' => fake()->optional(0.7, null)->passthrough(User::factory()),
        ];
    }
}
