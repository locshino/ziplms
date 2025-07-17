<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Collection;
use Spatie\Tags\Tag;

trait HasEnumTags
{
    /**
     * Create or retrieve tags based on the provided Enum class.
     *
     * @param  string  $enumClass  The Enum class that defines tag options and a key
     * @return \Illuminate\Support\Collection<Tag>
     */
    protected function createEnumTags(string $enumClass): Collection
    {
        // Get the keys from the enum options and create/find tags with the enum's key as the type
        $tagNames = $enumClass::values();
        $tagClassName = config('tags.tag_model', Tag::class);

        return $tagClassName::findOrCreate($tagNames, $enumClass::key());
    }

    /**
     * Attach a random tag from the given tag collection to the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Illuminate\Support\Collection<Tag>  $tags
     */
    protected function syncRandomTags($model, Collection $tags): void
    {
        if ($tags->isNotEmpty()) {
            // Pick a random tag and sync it to the model
            $model->syncTags([$tags->random()]);
        }
    }

    /**
     * Automatically create tags from enum and assign one random tag to the given model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     */
    protected function assignRandomTagFromEnum(string $enumClass, $model): void
    {
        // Create or find the tags based on the enum class
        $tags = $this->createEnumTags($enumClass);

        // Assign a random tag to the model
        $this->syncRandomTags($model, $tags);
    }
}
