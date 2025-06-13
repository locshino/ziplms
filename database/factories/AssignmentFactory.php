<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

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
            'title' => ['vi' => 'Bài tập: '.$title, 'en' => 'Assignment: '.$title],
            'instructions' => ['vi' => fake()->paragraph, 'en' => fake()->paragraph],
            'assignment_type' => fake()->randomElement(['file_submission', 'online_text', 'quiz']),
            'max_score' => fake()->randomElement([10, 20, 50, 100]),
            'due_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'allow_late_submissions' => fake()->boolean,
            'created_by' => User::factory(),
        ];
    }
}
