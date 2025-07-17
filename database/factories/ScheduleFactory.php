<?php

namespace Database\Factories;

use App\Enums\SchedulableType;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    use Concerns\HasAssignsRandomOrNewModel,
        Concerns\HasFakesTranslations;

    protected $model = Schedule::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Lấy ngẫu nhiên một loại từ SchedulableType Enum
        $schedulableTypeEnum = fake()->randomElement(SchedulableType::cases());
        // Lấy class Model tương ứng
        $schedulableModelClass = $schedulableTypeEnum->getModelClass();
        $schedulableId = $this->assignRandomOrNewModel($schedulableModelClass);

        return [
            'schedulable_id' => $schedulableId,
            'schedulable_type' => $schedulableModelClass,
            'title' => $this->fakeSentenceTranslations(),
            'description' => $this->fakeParagraphTranslations(),
            'assigned_id' => $this->assignRandomOrNewModel(User::class),
            'start_time' => fake()->dateTimeBetween('+1 day', '+1 week'),
            'end_time' => fake()->dateTimeBetween('+1 week', '+2 weeks'),
            'location_id' => $this->assignRandomOrNewModel(Location::class),
            'created_by' => $this->assignRandomOrNewModel(User::class),
        ];
    }
}
