<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Interface EloquentRepositoryInterface
 * @package App\Repositories\Interfaces
 */
interface EloquentRepositoryInterface
{
    /**
     * Get all models.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Find a model by its primary key.
     *
     * @param mixed $modelId
     * @param array $columns
     * @param array $relations
     * @param array $appends
     * @return Model|null
     */
    public function findById(
        mixed $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model;

    /**
     * Create a new model.
     *
     * @param array $payload
     * @return Model
     */
    public function create(array $payload): Model;

    /**
     * Update a model by its primary key.
     *
     * @param mixed $modelId
     * @param array $payload
     * @return bool
     */
    public function updateById(mixed $modelId, array $payload): bool;

    /**
     * Delete a model by its primary key.
     *
     * @param mixed $modelId
     * @return bool
     */
    public function deleteById(mixed $modelId): bool;
}
