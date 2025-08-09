<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\Repositories\PermissionRepositoryException;
use App\Exceptions\Repositories\RepositoryException;
use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use Exception;
use Illuminate\Support\Collection;

/**
 * Permission repository implementation.
 *
 * Handles permission data access operations with business rules enforcement.
 *
 * @throws RepositoryException When general repository operations fail
 * @throws PermissionRepositoryException When permission-specific operations fail
 */
class PermissionRepository extends EloquentRepository implements PermissionRepositoryInterface
{
    /**
     * Get the model class name.
     *
     * @return string
     */
    protected function model(): string
    {
        return Permission::class;
    }

    /**
     * Get all non-system permissions.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getAllNonSystemPermissions(array $columns = ['*'], array $relations = []): Collection
    {
        try {
            $query = $this->model->where('is_system', false);

            if (!empty($relations)) {
                $query->with($relations);
            }

            return $query->get($columns);
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get permissions by guard name (non-system only).
     *
     * @param string $guardName
     * @param array $columns
     * @param array $relations
     * @return Collection
     * @throws PermissionRepositoryException When invalid guard name provided
     * @throws RepositoryException When database error occurs
     */
    public function getByGuardName(string $guardName, array $columns = ['*'], array $relations = []): Collection
    {
        try {
            if (empty($guardName)) {
                throw PermissionRepositoryException::invalidGuardName($guardName);
            }

            $query = $this->model->where('guard_name', $guardName)
                               ->where('is_system', false);

            if (!empty($relations)) {
                $query->with($relations);
            }

            return $query->get($columns);
        } catch (PermissionRepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Check if permission exists by name (non-system only).
     *
     * @param string $name
     * @param string|null $guardName
     * @return bool
     * @throws PermissionRepositoryException When invalid permission format provided
     * @throws RepositoryException When database error occurs
     */
    public function existsByName(string $name, ?string $guardName = null): bool
    {
        try {
            if (empty($name)) {
                throw PermissionRepositoryException::invalidPermissionFormat($name);
            }

            $query = $this->model->where('name', $name)
                               ->where('is_system', false);

            if ($guardName) {
                $query->where('guard_name', $guardName);
            }

            return $query->exists();
        } catch (PermissionRepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Override the base all method to exclude system permissions by default.
     *
     * @param array $columns
     * @param array $relations
     * @return Collection
     */
    public function all(array $columns = ['*'], array $relations = []): Collection
    {
        return $this->getAllNonSystemPermissions($columns, $relations);
    }

    /**
     * Override create method to ensure is_system = false and validate permission name.
     *
     * @param array $data
     * @return Permission
     * @throws PermissionRepositoryException When permission name already exists or invalid format
     * @throws RepositoryException When database error occurs
     */
    public function create(array $data): Permission
    {
        try {
            // Check if permission name already exists
            if ($this->model->where('name', $data['name'])->exists()) {
                throw PermissionRepositoryException::permissionNameExists($data['name']);
            }

            // Validate permission format if needed
            if (empty($data['name']) || !is_string($data['name'])) {
                throw PermissionRepositoryException::invalidPermissionFormat($data['name'] ?? null);
            }

            // Ensure is_system is always false for new permissions
            $data['is_system'] = false;

            return $this->model->create($data);
        } catch (PermissionRepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Update a permission.
     *
     * @param string $id
     * @param array $data
     * @return Permission
     * @throws PermissionRepositoryException When permission not found, name already exists, or invalid format
     * @throws RepositoryException When database error occurs
     */
    public function update(string $id, array $data): Permission
    {
        try {
            $permission = $this->model->find($id);
            if (!$permission) {
                throw PermissionRepositoryException::notFound($id);
            }

            // Check if name already exists (excluding current permission)
            if (isset($data['name']) && $data['name'] !== $permission->name) {
                if ($this->model->where('name', $data['name'])->exists()) {
                    throw PermissionRepositoryException::permissionNameExists($data['name']);
                }
            }

            // Validate permission format if name is being updated
            if (isset($data['name']) && (empty($data['name']) || !is_string($data['name']))) {
                throw PermissionRepositoryException::invalidPermissionFormat($data['name']);
            }

            // Ensure is_system remains false for non-system permissions
            $data['is_system'] = false;

            $permission->update($data);
            return $permission->fresh();
        } catch (PermissionRepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Delete a permission.
     *
     * @param string $id
     * @return bool
     * @throws PermissionRepositoryException When permission not found or in use by roles
     * @throws RepositoryException When database error or deletion fails
     */
    public function delete(string $id): bool
    {
        try {
            $permission = $this->model->find($id);
            if (!$permission) {
                throw PermissionRepositoryException::notFound($id);
            }

            // Check if permission is assigned to any roles
            if ($permission->roles()->exists()) {
                throw PermissionRepositoryException::permissionInUseByRoles(
                    $permission->name
                );
            }

            if (!$permission->delete()) {
                throw RepositoryException::deleteFailed($permission->id, 'Failed to delete permission');
            }

            return true;
        } catch (PermissionRepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }
}
