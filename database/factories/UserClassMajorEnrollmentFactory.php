<?php

namespace Database\Factories;

use App\Models\ClassesMajor;
use App\Models\User;
use App\Models\UserClassMajorEnrollment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserClassMajorEnrollment>
 */
class UserClassMajorEnrollmentFactory extends Factory
{
    protected $model = UserClassMajorEnrollment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'class_major_id' => ClassesMajor::factory(),
            'start_date' => fake()->date(),
            'end_date' => fake()->optional()->date(),
        ];
    }
}
