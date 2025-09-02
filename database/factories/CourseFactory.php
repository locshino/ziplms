<?php

namespace Database\Factories;

use App\Enums\Status\CourseStatus;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->unique()->sentence(3);

        // Generate start_at first
        $startAt = $this->faker->dateTimeBetween('-1 month', '+1 month');

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(5),
            'price' => $this->faker->randomElement([null, $this->faker->randomFloat(2, 10, 500)]),
            'is_featured' => $this->faker->boolean(15), // 15% chance of being featured
            'teacher_id' => User::factory(),
            'start_at' => $startAt,
            'end_at' => $this->faker->dateTimeInInterval($startAt->format('Y-m-d H:i:s'), '+8 months'),
            'status' => $this->faker->randomElement(CourseStatus::cases())->value,
        ];
    }

    /**
     * Indicate that the course is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the course has a specific status.
     */
    public function withStatus(CourseStatus $status): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => $status->value,
        ]);
    }
}
