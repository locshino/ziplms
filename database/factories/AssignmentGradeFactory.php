<?php

namespace Database\Factories;

use App\Models\AssignmentGrade;
use App\Models\AssignmentSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AssignmentGrade>
 */
class AssignmentGradeFactory extends Factory
{
    protected $model = AssignmentGrade::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'submission_id' => AssignmentSubmission::factory(),
            'grade' => fake()->randomFloat(1, 5, 10),
            'feedback' => ['vi' => fake()->sentence, 'en' => fake()->sentence],
            'graded_by' => User::factory(),
            'graded_at' => now(),
        ];
    }
}
