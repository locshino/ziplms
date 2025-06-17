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
        $nameVi = 'Lớp '.fake()->bothify('1#??');
        $nameEn = 'Class '.fake()->bothify('1#??');

        return [
            'organization_id' => Organization::factory(),
            'name' => ['vi' => $nameVi, 'en' => $nameEn],
            'code' => strtoupper(fake()->unique()->bothify('CL-###')),
            'description' => ['vi' => fake()->sentence, 'en' => fake()->sentence],
            'parent_id' => null,
        ];
    }
}
