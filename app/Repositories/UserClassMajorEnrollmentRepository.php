<?php

namespace App\Repositories;

use App\Models\ClassesMajor;
use Illuminate\Database\Eloquent\Builder;

class UserClassMajorEnrollmentRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getClassMajorFilterOptions(): array
    {
        return ClassesMajor::query()->pluck('name', 'id')->toArray();
    }

    public function applyClassMajorFilter(Builder $query, $classMajorId): Builder
    {
        if (! empty($classMajorId)) {
            $query->where('class_major_id', $classMajorId);
        }

        return $query;
    }
}
