<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface BaseServiceInterface
{
    /**
     * Get all models.
     */
    public function all(): Collection;

    /**
     * Find a model by its primary key.
     */
    public function findById(mixed $id): ?Model;

    /**
     * Create a new model.
     */
    public function create(array $payload): Model;

    /**
     * Update a model by its primary key.
     */
    public function updateById(mixed $id, array $payload): bool;

    /**
     * Delete a model by its primary key.
     */
    public function deleteById(mixed $id): bool;
}
