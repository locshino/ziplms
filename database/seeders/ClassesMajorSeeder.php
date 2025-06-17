<?php

namespace Database\Seeders;

use App\Enums\ClassesMajorType;
use App\Models\ClassesMajor;
use Illuminate\Database\Seeder;
use Spatie\Tags\Tag;

class ClassesMajorSeeder extends Seeder
{
    use Concerns\HasEnumTags;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create or retrieve all classes/major type tags
        $classMajorTypeTags = $this->createEnumTags(ClassesMajorType::class);

        // 2. Create parent classes/majors and attach a random type tag
        $parentClassesMajors = ClassesMajor::factory()
            ->count(5)
            ->create()
            ->each(function (ClassesMajor $classesMajor) use ($classMajorTypeTags) {
                $this->syncRandomTags($classesMajor, $classMajorTypeTags);
            });

        // 3. Create child classes/majors, assign a parent, and attach a random type tag
        if ($parentClassesMajors->isEmpty()) {
            return; // No parent classes/majors to create children for
        }

        // Create child classes/majors with a random parent from the created parent classes/majors
        ClassesMajor::factory()
            ->count(10)
            ->create()
            ->each(function (ClassesMajor $childClassesMajor) use ($parentClassesMajors, $classMajorTypeTags) {
                // Assign a random parent_id from the created parent classes/majors
                $childClassesMajor->parent_id = $parentClassesMajors->random()->id;
                $childClassesMajor->save();

                $this->syncRandomTags($childClassesMajor, $classMajorTypeTags);
            });
    }
}
