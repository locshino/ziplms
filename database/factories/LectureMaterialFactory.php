<?php

namespace Database\Factories;

use App\Models\Lecture;
use App\Models\LectureMaterial;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LectureMaterial>
 */
class LectureMaterialFactory extends Factory
{
    protected $model = LectureMaterial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'lecture_id' => Lecture::factory(),
            'name' => ['vi' => 'Tài liệu: '.$name, 'en' => 'Material: '.$name],
            'description' => ['vi' => fake()->sentence, 'en' => fake()->sentence],
            'uploaded_by' => User::factory(),
        ];
    }
}
