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
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraph(3),
            'price' => $this->faker->randomFloat(2, 0, 999.99),
            'is_featured' => $this->faker->boolean(20), // 20% chance of being featured
            'teacher_id' => User::factory(),
            'start_at' => $this->faker->dateTimeBetween('now', '+1 month'),
            'end_at' => $this->faker->dateTimeBetween('+1 month', '+6 months'),
            'status' => CourseStatus::PUBLISHED->value,
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
