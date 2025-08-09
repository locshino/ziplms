<?php

namespace App\Services\Interfaces;

use App\Models\Role;
use Illuminate\Support\Collection;

/**
 * Role service interface.
 *
 * Defines contract for role business logic operations.
 */
interface RoleServiceInterface extends BaseServiceInterface
{
    /**
     * Get all non-system roles for display.
     *
     * @return Collection
     */
    public function getAllNonSystemRoles(): Collection;

    /**
     * Create a new role with validation.
     *
     * @param array $data
     * @return Role
     * @throws \App\Exceptions\Services\ServiceException
     */
    public function createRole(array $data): Role;

    /**
     * Update role with validation.
     *
     * @param string $id
     * @param array $data
     * @return Role
     * @throws \App\Exceptions\Services\ServiceException
     */
    public function updateRole(string $id, array $data): Role;

    /**
     * Delete role with system role protection.
     *
     * @param string $id
     * @return bool
     * @throws \App\Exceptions\Services\ServiceException
     */
    public function deleteRole(string $id): bool;

    /**
     * Check if role can be deleted.
     *
     * @param string $id
     * @return bool
     */
    public function canDeleteRole(string $id): bool;

    /**
     * Get role with permissions.
     *
     * @param string $id
     * @return Role|null
     */
    public function getRoleWithPermissions(string $id): ?Role;

    /**
     * Process form data before saving role.
     *
     * @param \App\Models\Role $role
     * @param array $data
     * @return array Processed data
     * @throws \App\Exceptions\Services\ServiceException
     */
    public function processFormDataBeforeSave(\App\Models\Role $role, array $data): array;

    /**
     * Sync permissions to role after save.
     *
     * @param \App\Models\Role $role
     * @param array $processedData
     * @return void
     */
    public function syncPermissionsAfterSave(\App\Models\Role $role, array $processedData): void;
}
