<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\Exam;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(4);

        return [
            'course_id' => fake()->optional(0.8, null)->passthrough(Course::factory()),
            'lecture_id' => fake()->optional(0.5, null)->passthrough(Lecture::factory()),
            'title' => ['vi' => 'Bài kiểm tra: '.$title, 'en' => 'Exam: '.$title],
            'description' => ['vi' => fake()->paragraph, 'en' => fake()->paragraph],
            'start_time' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90, 120]),
            'max_attempts' => fake()->numberBetween(1, 3),
            'passing_score' => fake()->randomFloat(1, 5, 10),
            'shuffle_questions' => fake()->boolean,
            'shuffle_answers' => fake()->boolean,
            'show_results_after' => fake()->randomElement(['immediately', 'after_end_time', 'manual']),
            'created_by' => User::factory(),
        ];
    }
}
