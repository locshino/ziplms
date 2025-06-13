<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        $vietnameseCatchPhrase = fake()->catchPhrase;
        $englishCatchPhrase = fake()->catchPhrase;

        return [
            'name' => ['vi' => 'Khóa học '.$vietnameseCatchPhrase, 'en' => 'Course '.$englishCatchPhrase],
            'code' => strtoupper(fake()->unique()->bothify('??-###')),
            'description' => ['vi' => fake()->paragraph, 'en' => fake()->paragraph],
            'start_date' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'end_date' => fake()->dateTimeBetween('+3 months', '+6 months'),
        ];
    }
}
