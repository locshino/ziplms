<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Schedule; // Example schedulable
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Example: Default to Course as schedulable. Adjust as needed.
        $schedulable = Course::factory()->create();
        $title = fake()->sentence(3);

        return [
            'schedulable_id' => $schedulable->id,
            'schedulable_type' => get_class($schedulable),
            'title' => ['vi' => 'Lịch học: '.$title, 'en' => 'Schedule: '.$title],
            'description' => ['vi' => fake()->paragraph, 'en' => fake()->paragraph],
            'assigned_teacher_id' => User::factory(),
            'start_time' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'location_type' => fake()->randomElement(['online', 'offline_room', 'virtual_classroom']),
            'location_details' => fake()->address,
            'created_by' => User::factory(),
        ];
    }
}
