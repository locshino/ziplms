<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseServiceInterface
{
    /**
     * Get all models.
     *
     * @return Collection
     */
    public function all(): Collection;

    /**
     * Find a model by its primary key.
     *
     * @param mixed $id
     * @return Model|null
     */
    public function findById(mixed $id): ?Model;

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
     * @param mixed $id
     * @param array $payload
     * @return bool
     */
    public function updateById(mixed $id, array $payload): bool;

    /**
     * Delete a model by its primary key.
     *
     * @param mixed $id
     * @return bool
     */
    public function deleteById(mixed $id): bool;
}
