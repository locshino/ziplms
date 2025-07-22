<?php

namespace App\Repositories;

use App\Models\ClassesMajor;
use Illuminate\Database\Eloquent\Builder;

class ClassesMajorRepository
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function getParentOptions(): array
    {
        return ClassesMajor::query()
            ->select('id', 'name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function applyParentFilter(Builder $query, $parentId): Builder
    {
        if (! empty($parentId)) {
            $query->where('parent_id', $parentId);
        }

        return $query;
    }
}
