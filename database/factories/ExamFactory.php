<?php

namespace Database\Factories;

use App\Enums\ExamShowResultsType;
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
    use Concerns\HasAssignsRandomOrNewModel,
        Concerns\HasFakesStatus,
        Concerns\HasFakesTranslations;

    protected $model = Exam::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'course_id' => $this->assignRandomOrNewModel(Course::class),
            'lecture_id' => $this->assignRandomOrNewModel(Lecture::class),
            'title' => $this->fakeSentenceTranslations(),
            'description' => $this->fakeParagraphTranslations(),
            'start_time' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90, 120]),
            'max_attempts' => fake()->numberBetween(1, 3),
            'passing_score' => fake()->randomFloat(1, 5, 10),
            'shuffle_questions' => fake()->boolean,
            'shuffle_answers' => fake()->boolean,
            'show_results_after' => fake()->randomElement(ExamShowResultsType::values()),
            'created_by' => $this->assignRandomOrNewModel(User::class),
            'status' => $this->fakeStatus(\App\States\Exam\Status::class),
        ];
    }
}
