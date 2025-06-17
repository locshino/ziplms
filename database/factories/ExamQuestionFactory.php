<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\ExamQuestion;
use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ExamQuestion>
 */
class ExamQuestionFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel;

    protected $model = ExamQuestion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $scoringScale = 10; // Define the scoring scale (Ex: 5, 10, 100, etc.)
        $points = fake()->randomFloat(2, 0.5, 10.0) * $scoringScale / 10; // Random points between 0.5 and 10, adjusted by the scoring scale

        return [
            'exam_id' => $this->assignRandomOrNewModel(Exam::class),
            'question_id' => $this->assignRandomOrNewModel(Question::class),
            'points' => $points,
            'question_order' => fake()->numberBetween(1, 100),
        ];
    }
}
