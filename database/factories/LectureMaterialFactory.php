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
    use Concerns\HasAssignsRandomOrNewModel,
        Concerns\HasFakesTranslations;

    protected $model = LectureMaterial::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lecture_id' => $this->assignRandomOrNewModel(Lecture::class),
            'uploaded_by' => $this->assignRandomOrNewModel(User::class),
            'name' => $this->fakeSentenceTranslations(),
            'description' => $this->fakeParagraphTranslations(),
            'video_links' => json_encode([
                'youtube' => [
                    [
                        'title' => $this->fakeSentenceTranslations(),
                        'url' => fake()->url(),
                    ],
                    [
                        'title' => $this->fakeSentenceTranslations(),
                        'url' => fake()->url(),
                    ],
                ],
                'vimeo' => [
                    [
                        'title' => $this->fakeSentenceTranslations(),
                        'url' => fake()->url(),
                    ],
                    [
                        'title' => $this->fakeSentenceTranslations(),
                        'url' => fake()->url(),
                    ],
                ],
            ]),
        ];
    }
}
