<?php

namespace App\Repositories\Interfaces;

use App\Models\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get all non-system permissions.
     */
    public function getAllNonSystemPermissions(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get permissions by guard name (non-system only).
     */
    public function getByGuardName(string $guardName, array $columns = ['*'], array $relations = []): Collection;

    /**
     * Check if permission exists by name (non-system only).
     */
    public function existsByName(string $name, ?string $guardName = null): bool;

    /**
     * Create a new permission.
     */
    public function create(array $data): Permission;

    /**
     * Update a permission.
     */
    public function update(string $id, array $data): Permission;

    /**
     * Delete a permission.
     */
    public function delete(string $id): bool;
}
