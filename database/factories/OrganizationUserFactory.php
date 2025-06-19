<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrganizationUser>
 */
class OrganizationUserFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => $this->assignRandomOrNewModel(\App\Models\User::class),
            'organization_id' => $this->assignRandomOrNewModel(\App\Models\Organization::class),
        ];
    }
}
