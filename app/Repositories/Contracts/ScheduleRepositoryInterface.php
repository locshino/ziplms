<?php

namespace App\Repositories\Contracts;

use App\Models\Schedule;
use App\Repositories\Contracts\Base\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface ScheduleRepositoryInterface
 */
interface ScheduleRepositoryInterface extends RepositoryInterface
{
    /**
     * Get the display title for the 'schedulable' polymorphic relationship.
     *
     * @param  Schedule  $schedule  The schedule model instance.
     * @return string|null The display title or null if not found.
     */
    public function getSchedulableTitle(Schedule $schedule): ?string;

    /**
     * Apply a filter to the query based on location type tags.
     *
     * @param  Builder  $query  The query builder instance.
     * @param  array  $tags  An array of tag names to filter by.
     * @return Builder The modified query builder.
     */
    public function applyTagFilter(Builder $query, array $tags): Builder;
}
