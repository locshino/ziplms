<?php

namespace Database\Factories;

use App\Models\Location;
use App\States\Location\LocationStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
    use Concerns\HasFakesStatus;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Location::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => 'Campus '.fake()->city(),
                'vi' => 'Cơ sở '.fake('vi_VN')->city(),
            ],
            'address' => [
                'en' => fake()->address(),
                'vi' => fake('vi_VN')->address(),
            ],
            'locate' => [
                'lat' => fake()->latitude(8.18, 23.39),
                'lng' => fake()->longitude(102.14, 109.46),
            ],
            'status' => $this->fakeStatus(LocationStatus::class),
        ];
    }
}
