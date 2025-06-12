<?php

namespace Database\Factories;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ContactMessage>
 */
class ContactMessageFactory extends Factory
{
    protected $model = ContactMessage::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subject = fake()->sentence(4);

        return [
            'sender_id' => User::factory(),
            'receiver_id' => User::factory(),
            'subject' => ['vi' => 'Chủ đề: '.$subject, 'en' => 'Subject: '.$subject],
            'message' => fake()->paragraph,
            'sent_at' => now(),
            'read_at' => fake()->optional()->dateTime,
        ];
    }
}
