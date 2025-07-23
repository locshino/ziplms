<?php

namespace Database\Factories;

use App\Models\Course;
use App\Models\CourseEnrollment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CourseEnrollment>
 */
class CourseEnrollmentFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel,
        Concerns\HasFakesStatus;

    protected $model = CourseEnrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->assignRandomOrNewModel(User::class),
            'course_id' => $this->assignRandomOrNewModel(Course::class),
            'enrollment_date' => now(),
            'final_grade' => fake()->optional()->randomFloat(1, 0, 10),
            'completed_at' => fake()->optional()->dateTime,
            'status' => $this->fakeStatus(\App\States\Course\CourseStatus::class),
        ];
    }
}
