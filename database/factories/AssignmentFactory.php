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
    use Concerns\HasFakesTranslations,
        Concerns\HasAssignsRandomOrNewModel;

    protected $model = Assignment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => $this->assignRandomOrNewModel(Course::class),
            'title' => $this->fakeSentenceTranslations(),
            'instructions' => $this->fakeParagraphTranslations(),
            'max_score' => fake()->randomElement([10, 20, 50, 100]),
            'due_date' => fake()->dateTimeBetween('+1 week', '+1 month'),
            'allow_late_submissions' => fake()->boolean,
        ];
    }
}
