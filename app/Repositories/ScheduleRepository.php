<?php

namespace App\Repositories;

use App\Enums\LocationType;
use App\Enums\SchedulableType;
use App\Models\Schedule;
use App\Repositories\Base\Repository;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class ScheduleRepository
 */
class ScheduleRepository extends Repository implements ScheduleRepositoryInterface
{
    /**
     * Specify the model class name.
     */
    protected function model(): string
    {
        return Schedule::class;
    }

    /**
     * Get the display title for the 'schedulable' polymorphic relationship.
     *
     * @param  Schedule  $schedule  The schedule model instance.
     * @return string|null The display title or null if not found.
     */
    public function getSchedulableTitle(Schedule $schedule): ?string
    {
        // Ensure the relationship is loaded and not null
        if (! $schedule->schedulable) {
            return null;
        }

        // Find the enum case based on the 'schedulable_type' from the record
        $enumCase = SchedulableType::tryFrom($schedule->schedulable_type);

        // If enum case is not found, fallback for safety
        if (! $enumCase) {
            return $schedule->schedulable->name ?? $schedule->schedulable->title ?? null;
        }

        // Get the title column from the enum and return the corresponding value
        return $schedule->schedulable->{$enumCase->getTitleColumn()};
    }

    /**
     * Apply a filter to the query based on location type tags.
     *
     * @param  Builder  $query  The query builder instance.
     * @param  array  $tags  An array of tag names to filter by.
     * @return Builder The modified query builder.
     */
    public function applyTagFilter(Builder $query, array $tags): Builder
    {
        if (empty($tags)) {
            return $query;
        }

        // Use the scope provided by spatie/laravel-tags
        return $query->withAnyTags($tags, LocationType::key());
    }
}
