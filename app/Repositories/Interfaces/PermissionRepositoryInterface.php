<?php

namespace App\Repositories\Interfaces;

use App\Models\Permission;
use Illuminate\Support\Collection;

interface PermissionRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get all non-system permissions.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getAllNonSystemPermissions(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get permissions by guard name (non-system only).
     *
     * @param string $guardName
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function getByGuardName(string $guardName, array $columns = ['*'], array $relations = []): Collection;

    /**
     * Check if permission exists by name (non-system only).
     *
     * @param string $name
     * @param string|null $guardName
     * @return bool
     */
    public function existsByName(string $name, ?string $guardName = null): bool;

    /**
     * Create a new permission.
     *
     * @param array $data
     * @return Permission
     */
    public function create(array $data): Permission;

    /**
     * Update a permission.
     *
     * @param string $id
     * @param array $data
     * @return Permission
     */
    public function update(string $id, array $data): Permission;

    /**
     * Delete a permission.
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}