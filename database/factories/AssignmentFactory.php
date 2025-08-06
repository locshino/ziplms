<?php

namespace Database\Factories;

use App\Models\Assignment;
use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Assignment>
 */
class AssignmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Assignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startAt = $this->faker->dateTimeBetween('now', '+1 month');
        $dueAt = $this->faker->dateTimeBetween($startAt, $startAt->format('Y-m-d H:i:s').' +2 months');
        $gradingAt = $this->faker->dateTimeBetween($dueAt, $dueAt->format('Y-m-d H:i:s').' +1 week');
        $endAt = $this->faker->dateTimeBetween($gradingAt, $gradingAt->format('Y-m-d H:i:s').' +2 weeks');

        return [
            'course_id' => Course::factory(),
            'title' => $this->faker->sentence(4),
            'instructions' => $this->faker->paragraphs(3, true),
            'max_points' => $this->faker->randomFloat(2, 50, 100),
            'late_penalty_percentage' => $this->faker->optional(0.7)->randomFloat(2, 5, 25),
            'start_at' => $startAt,
            'due_at' => $dueAt,
            'grading_at' => $gradingAt,
            'end_at' => $endAt,
        ];
    }
}
