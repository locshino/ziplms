<?php

namespace App\Repositories\Interfaces;

use App\Models\Role;
use Illuminate\Support\Collection;

/**
 * Role repository interface.
 *
 * Defines contract for role data access operations.
 */
interface RoleRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get all non-system roles.
     *
     * @return Collection
     */
    public function getNonSystemRoles(): Collection;

    /**
     * Check if role is system role.
     *
     * @param string $id
     * @return bool
     */
    public function isSystemRole(string $id): bool;

    /**
     * Create a new role with is_system = false.
     *
     * @param array $data
     * @return Role
     */
    public function createNonSystemRole(array $data): Role;

    /**
     * Delete role if it's not a system role.
     *
     * @param string $id
     * @return bool
     * @throws \Exception When trying to delete system role
     */
    public function safeDelete(string $id): bool;
}