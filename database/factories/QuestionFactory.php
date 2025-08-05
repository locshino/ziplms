<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Question::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'quiz_id' => Quiz::factory(),
            'title' => $this->faker->sentence() . '?',
            'points' => $this->faker->randomFloat(2, 1, 10),
            'is_multiple_response' => $this->faker->boolean(20),
        ];
    }

    /**
     * Indicate that the question allows multiple responses.
     */
    public function multipleResponse(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_multiple_response' => true,
        ]);
    }

    /**
     * Indicate that the question allows single response only.
     */
    public function singleResponse(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_multiple_response' => false,
        ]);
    }
}