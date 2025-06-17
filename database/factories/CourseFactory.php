<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    use Concerns\HasFakesStatus,
        Concerns\HasFakesTranslations;

    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'name' => $this->fakeSentenceTranslations(),
            'code' => strtoupper(fake()->unique()->bothify('??-###')),
            'description' => $this->fakeParagraphTranslations(),
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => fake()->dateTimeBetween('+3 months', '+6 months'),
            'status' => $this->fakeStatus(90),
        ];
    }
}
