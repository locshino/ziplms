<?php

namespace App\Services\Interfaces;

use App\Models\Permission;
use Illuminate\Support\Collection;

interface PermissionServiceInterface extends BaseServiceInterface
{
    /**
     * Get all non-system permissions.
     *
     * @return Collection
     */
    public function getAllNonSystemPermissions(): Collection;

    /**
     * Get permissions by guard name (non-system only).
     *
     * @param string $guardName
     * @return Collection
     */
    public function getByGuardName(string $guardName): Collection;

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
    public function createPermission(array $data): Permission;

    /**
     * Update a permission.
     *
     * @param string $id
     * @param array $data
     * @return Permission
     */
    public function updatePermission(string $id, array $data): Permission;

    /**
     * Delete a permission.
     *
     * @param string $id
     * @return bool
     */
    public function deletePermission(string $id): bool;

    /**
     * Get permissions for dropdown/select options (non-system only).
     *
     * @return array
     */
    public function getPermissionOptions(): array;

    /**
     * Create new permissions from form data.
     *
     * @param array $newPermissions Array of permission data
     * @return \Illuminate\Support\Collection Collection of created permission names
     */
    public function createNewPermissions(array $newPermissions): \Illuminate\Support\Collection;

    /**
     * Get existing custom permissions (non-system permissions).
     *
     * @return \Illuminate\Support\Collection
     */
    public function getExistingCustomPermissions(): \Illuminate\Support\Collection;

    /**
     * Validate permission data structure.
     *
     * @param array $permissionData
     * @return bool
     */
    public function validatePermissionStructure(array $permissionData): bool;
}