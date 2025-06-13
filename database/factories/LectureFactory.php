<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Lecture>
 */
class LectureFactory extends Factory
{
    protected $model = Lecture::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(3);

        return [
            'course_id' => Course::factory(),
            'title' => ['vi' => 'Bài giảng: '.$title, 'en' => 'Lecture: '.$title],
            'description' => ['vi' => fake()->paragraph, 'en' => fake()->paragraph],
            'lecture_order' => fake()->numberBetween(1, 20),
            'duration_estimate' => fake()->randomElement(['30 phút', '1 giờ', '90 phút', '2 giờ']),
            'created_by' => User::factory(),
        ];
    }
}
