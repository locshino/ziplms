<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface EloquentRepositoryInterface
 */
interface EloquentRepositoryInterface
{
    /**
     * Get all models.
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Find a model by its primary key.
     */
    public function findById(
        mixed $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model;

    /**
     * Create a new model.
     */
    public function create(array $payload): Model;

    /**
     * Update a model by its primary key.
     */
    public function updateById(mixed $modelId, array $payload): bool;

    /**
     * Delete a model by its primary key.
     */
    public function deleteById(mixed $modelId): bool;
}
