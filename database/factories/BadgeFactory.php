<?php

namespace Database\Factories;

use App\Models\Badge;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Badge>
 */
class BadgeFactory extends Factory
{
    protected $model = Badge::class;

    public function definition(): array
    {
        $vietnameseWord = fake()->word; // Generate one Vietnamese-like word for consistency if needed, or use separate fakes
        $englishWord = fake()->word;

        return [
            'name' => ['vi' => 'Huy hiệu '.$vietnameseWord, 'en' => 'Badge of '.$englishWord],
            'description' => ['vi' => fake()->sentence, 'en' => fake()->sentence],
            'criteria_description' => ['vi' => 'Hoàn thành xuất sắc nhiệm vụ.', 'en' => 'For excellent completion of tasks.'],
        ];
    }
}
