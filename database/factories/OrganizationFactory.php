<?php

namespace Database\Factories;

use App\Models\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Organization>
 */
class OrganizationFactory extends Factory
{
    protected $model = Organization::class;

    public function definition(): array
    {
        $name = fake()->company;

        return [
            'name' => ['vi' => $name, 'en' => $name],
            'slug' => Str::slug($name),
            'type' => fake()->randomElement(['university', 'high_school', 'training_center']),
            'address' => ['vi' => fake()->address, 'en' => fake()->address],
            'phone_number' => fake()->phoneNumber,
            'settings' => json_encode(['theme' => 'default']),
        ];
    }
}
