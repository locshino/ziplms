<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\Repositories\RepositoryException;
use App\Exceptions\Repositories\RoleRepositoryException;
use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use Exception;
use Illuminate\Support\Collection;

/**
 * Role repository implementation.
 *
 * Handles role data access operations with business rules enforcement.
 *
 * @throws RepositoryException When general repository operations fail
 * @throws RoleRepositoryException When role-specific operations fail
 */
class RoleRepository extends EloquentRepository implements RoleRepositoryInterface
{
    /**
     * Get the model class name.
     */
    protected function model(): string
    {
        return Role::class;
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException When database error occurs
     */
    public function getNonSystemRoles(): Collection
    {
        try {
            return $this->model->where('is_system', false)->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws RepositoryException When database error occurs
     */
    public function isSystemRole(string $id): bool
    {
        try {
            $role = $this->model->find($id);

            return $role ? (bool) $role->is_system : false;
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws RoleRepositoryException When role name already exists
     * @throws RepositoryException When database error occurs
     */
    public function createNonSystemRole(array $data): Role
    {
        try {
            // Check if role name already exists
            if ($this->model->where('name', $data['name'])->exists()) {
                throw RoleRepositoryException::roleNameExists($data['name']);
            }

            // Ensure is_system is always false for new roles
            $data['is_system'] = false;

            return $this->model->create($data);
        } catch (RoleRepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * {@inheritDoc}
     *
     * @throws RoleRepositoryException When role not found, is system role, or has users
     * @throws RepositoryException When database error occurs
     */
    public function safeDelete(string $id): bool
    {
        try {
            $role = $this->model->find($id);
            if (! $role) {
                throw RoleRepositoryException::notFound($id);
            }

            if ($this->isSystemRole($id)) {
                throw RoleRepositoryException::systemRoleProtected('delete', $role->name);
            }

            // Check if role has users
            $userCount = $role->users()->count();
            if ($userCount > 0) {
                throw RoleRepositoryException::roleHasUsers($role->name, $userCount);
            }

            return $role->delete();
        } catch (RoleRepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Override create method to ensure is_system = false.
     *
     * @throws RoleRepositoryException
     */
    public function create(array $data): Role
    {
        return $this->createNonSystemRole($data);
    }

    /**
     * Override delete method to prevent system role deletion.
     *
     * @throws RoleRepositoryException
     */
    public function delete(string $id): bool
    {
        return $this->safeDelete($id);
    }
}
