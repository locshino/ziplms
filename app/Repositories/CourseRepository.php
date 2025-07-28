<?php

namespace App\Repositories;

use App\Models\Course;
use App\Repositories\Base\Repository;
use App\Repositories\Contracts\CourseRepositoryInterface;
use App\States\Status;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Class CourseRepository
 */
class CourseRepository extends Repository implements CourseRepositoryInterface
{
    protected function model(): string
    {
        return Course::class;
    }

    public function getCourseStatusLabel(Course $course): string
    {
        /** @var Status $statusState */
        $statusState = $course->status;
        return $statusState::label();
    }

    public function getCourseStatusColor(Course $course): string
    {
        return $course->status->color();
    }

    public function getAvailableParents(?int $excludeId = null): Collection
    {
        return $this->query()
            ->when($excludeId, fn($query) => $query->where('id', '!=', $excludeId))
            ->pluck('name', 'id');
    }

    public function applyDateFilter(Builder $query, ?string $from, ?string $to): Builder
    {
        return $query
            ->when(
                $from,
                fn(Builder $q, $fromDate): Builder => $q->where('created_at', '>=', Carbon::parse($fromDate)->startOfDay()),
            )
            ->when(
                $to,
                fn(Builder $q, $untilDate): Builder => $q->where('created_at', '<=', Carbon::parse($untilDate)->endOfDay()),
            );
    }
}