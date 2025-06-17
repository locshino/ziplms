<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel,
        Concerns\HasFakesTranslations;

    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'organization_id' => $this->assignRandomOrNewModel(Organization::class),
            'title' => $this->fakeSentenceTranslations(),
            'description' => $this->fakeParagraphTranslations(),
            'start_time' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'location' => fake()->address,
            'created_by' => $this->assignRandomOrNewModel(User::class),
        ];
    }
}
