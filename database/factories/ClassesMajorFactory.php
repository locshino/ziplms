<?php

namespace Database\Factories;

use App\Models\ClassesMajor;
use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ClassesMajor>
 */
class ClassesMajorFactory extends Factory
{
    protected $model = ClassesMajor::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = 'Lớp '.fake()->bothify('1#??');

        return [
            'organization_id' => Organization::factory(),
            'name' => ['vi' => $name, 'en' => 'Class '.fake()->bothify('1#??')],
            'code' => strtoupper(fake()->unique()->bothify('CL-###')),
            'description' => ['vi' => fake()->sentence, 'en' => fake()->sentence],
            'type' => fake()->randomElement(['class', 'major', 'department', 'grade_level']),
            'parent_id' => null, // Or logic to pick an existing ClassesMajor
        ];
    }
}
