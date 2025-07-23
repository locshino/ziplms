<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamAttempt>
 */
class ExamAttemptFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel,
        Concerns\HasFakesStatus,
        Concerns\HasFakesTranslations;

    protected $model = ExamAttempt::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scoringScale = 10; // Define the scoring scale (Ex: 5, 10, 100, etc.)
        $score = fake()->randomFloat(2, 0.5, 10.0) * $scoringScale / 10; // Random scores between 0.5 and 10, adjusted by the scoring scale

        return [
            'exam_id' => $this->assignRandomOrNewModel(Exam::class),
            'user_id' => $this->assignRandomOrNewModel(User::class),
            'attempt_number' => fake()->numberBetween(1, 5), // Random attempt number between 1 and 5
            'started_at' => now(),
            'completed_at' => fake()->optional()->dateTimeInInterval(now(), '+2 hours'),
            'score' => $score,
            'time_spent_seconds' => fake()->optional()->numberBetween(600, 7200),
            'feedback' => $this->fakeSentenceTranslations(),
            // 'status' => $this->fakeStatus(),
        ];
    }
}
