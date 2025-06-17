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
    use Concerns\HasFakesStatus,
        Concerns\HasFakesTranslations,
        Concerns\HasAssignsRandomOrNewModel;

    protected $model = Lecture::class;

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
            'description' => $this->fakeParagraphTranslations(),
            'lecture_order' => fake()->numberBetween(1, 20),
            'duration_estimate' => fake()->randomElement(['30 phút', '1 giờ', '90 phút', '2 giờ']),
            'created_by' => $this->assignRandomOrNewModel(User::class),
            'status' => $this->fakeStatus(90), // 90% chance of being active
        ];
    }
}
