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
    use Concerns\HasFakesTranslations,
        Concerns\HasAssignsRandomOrNewModel;

    protected $model = AssignmentGrade::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'submission_id' => $this->assignRandomOrNewModel(AssignmentSubmission::class),
            'grade' => fake()->randomFloat(1, 5, 10),
            'feedback' => $this->fakeParagraphTranslations(),
            'graded_by' => $this->assignRandomOrNewModel(User::class),
            'graded_at' => now(),
        ];
    }
}
