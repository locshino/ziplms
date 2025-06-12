<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseStaffAssignment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseStaffAssignment>
 */
class CourseStaffAssignmentFactory extends Factory
{
    protected $model = CourseStaffAssignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'course_id' => Course::factory(),
            'role_in_course' => fake()->randomElement(['instructor', 'teaching_assistant', 'marker']),
            'assigned_at' => now(),
        ];
    }
}
