<?php

namespace App\Services;

use App\Exceptions\Repositories\RepositoryException;
use App\Exceptions\Services\RoleServiceException;
use App\Exceptions\Services\ServiceException;
use App\Libs\Roles\RoleHelper;
use App\Models\Role;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Services\Interfaces\PermissionServiceInterface;
use App\Services\Interfaces\RoleServiceInterface;
use BezhanSalleh\FilamentShield\Support\Utils;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

/**
 * Role service implementation.
 *
 * Handles role business logic with proper validation and system role protection.
 *
 * @throws ServiceException When role service operations fail
 * @throws RepositoryException When repository operations fail
 */
class RoleService extends BaseService implements RoleServiceInterface
{
    /**
     * RoleService constructor.
     */
    public function __construct(
        private RoleRepositoryInterface $roleRepository,
        private PermissionServiceInterface $permissionService
    ) {
        parent::__construct($roleRepository);
    }

    /**
     * Get all non-system roles.
     *
     * @throws RepositoryException When database error occurs
     */
    public function getAllNonSystemRoles(): Collection
    {
        try {
            return $this->roleRepository->getNonSystemRoles();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to retrieve non-system roles: '.$e->getMessage());
        }
    }

    /**
     * Create a new role.
     *
     * @throws RoleServiceException When role creation fails
     * @throws RepositoryException When repository operations fail
     */
    public function createRole(array $data): Role
    {
        try {
            // Validate input data
            $this->validateRoleData($data);

            return DB::transaction(function () use ($data) {
                // Create role with is_system = false (handled by repository)
                return $this->roleRepository->createNonSystemRole($data);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (RoleServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RoleServiceException::roleCreationFailed(
                $data['name'] ?? null,
                $e->getMessage()
            );
        }
    }

    /**
     * Update a role.
     *
     * @throws RoleServiceException When role update fails or system role modification attempted
     * @throws RepositoryException When repository operations fail
     */
    public function updateRole(string $id, array $data): Role
    {
        try {
            $isSystemRole = $this->roleRepository->isSystemRole($id);
            $isCurrentUserSuperAdmin = RoleHelper::isSuperAdmin();

            $canUpdateRole = ! $isSystemRole || $isCurrentUserSuperAdmin;
            // Check if role exists and is not system role
            if (! $canUpdateRole) {
                $role = $this->roleRepository->findById($id);
                throw RoleServiceException::systemRoleModificationAttempt(
                    'update',
                    $role ? $role->name : null
                );
            }

            // Validate input data
            $this->validateRoleData($data, $id);

            return DB::transaction(function () use ($id, $data, $isSystemRole) {
                // Only set is_system = false for non-system roles
                // System roles should maintain their is_system status
                if (! $isSystemRole) {
                    $data['is_system'] = false;
                }

                return $this->roleRepository->updateById($id, $data);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (RoleServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw RoleServiceException::roleUpdateFailed(
                $data['name'] ?? null,
                $e->getMessage()
            );
        }
    }

    /**
     * Delete a role.
     *
     * @throws RoleServiceException When role deletion fails
     * @throws RepositoryException When repository operations fail
     */
    public function deleteRole(string $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                // Safe delete will check if it's system role
                return $this->roleRepository->safeDelete($id);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            $role = $this->roleRepository->findById($id);
            throw RoleServiceException::roleDeletionFailed(
                $role ? $role->name : null,
                $e->getMessage()
            );
        }
    }

    /**
     * Check if a role can be deleted.
     *
     * @throws RepositoryException When database error occurs
     */
    public function canDeleteRole(string $id): bool
    {
        try {
            // Cannot delete system roles
            if ($this->roleRepository->isSystemRole($id)) {
                return false;
            }

            // Check if role exists
            $role = $this->roleRepository->findById($id);
            if (! $role) {
                return false;
            }

            // Additional business logic can be added here
            // For example, check if role has users assigned

            return true;
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to check role deletion eligibility: '.$e->getMessage());
        }
    }

    /**
     * Get role with its permissions.
     *
     * @throws RepositoryException When database error occurs
     */
    public function getRoleWithPermissions(string $id): ?Role
    {
        try {
            $role = $this->roleRepository->findById($id);

            if ($role) {
                $role->load('permissions');
            }

            return $role;
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get role with permissions: '.$e->getMessage());
        }
    }

    /**
     * Validate role data.
     *
     * @param  string|null  $roleId  For update validation
     *
     * @throws RoleServiceException When validation fails
     */
    private function validateRoleData(array $data, ?string $roleId = null): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'guard_name' => 'nullable|string|max:255',
        ];

        // Add unique rule for name
        if ($roleId) {
            $rules['name'] .= '|unique:roles,name,'.$roleId;
        } else {
            $rules['name'] .= '|unique:roles,name';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw RoleServiceException::roleNameValidationFailed(
                $data['name'] ?? null,
                implode(', ', $validator->errors()->all())
            );
        }
    }

    /**
     * Override the base all method to return non-system roles only.
     *
     * @throws RepositoryException When database error occurs
     */
    public function all(): Collection
    {
        try {
            return $this->getAllNonSystemRoles();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to retrieve all roles: '.$e->getMessage());
        }
    }

    /**
     * Process form data before saving role.
     *
     * @return array Processed data
     *
     * @throws RoleServiceException When system role modification attempted
     */
    public function processFormDataBeforeSave(Role $role, array $data): array
    {
        // Prevent editing system roles unless user is super admin
        if ($role->is_system && ! RoleHelper::isSuperAdmin()) {
            throw RoleServiceException::systemRoleModificationAttempt('update', $role->name);
        }

        // Handle new permissions creation
        $createdPermissions = $this->handleNewPermissions($data);

        // Filter permissions from data
        $permissions = collect($data)
            ->filter(function ($permission, $key) {
                return ! in_array($key, [
                    'name', 'guard_name', 'select_all', 'is_system',
                    'new_permissions', 'existing_custom_permissions',
                    Utils::getTenantModelForeignKey(),
                ]);
            })
            ->values()
            ->flatten()
            ->unique();

        // Add created permissions
        $permissions = $permissions->merge($createdPermissions);

        // Add existing custom permissions if selected
        if (isset($data['existing_custom_permissions']) && is_array($data['existing_custom_permissions'])) {
            $permissions = $permissions->merge($data['existing_custom_permissions']);
        }

        // Store permissions for later use
        $data['_processed_permissions'] = $permissions;

        // Only set is_system = false for non-system roles
        if (! $role->is_system) {
            $data['is_system'] = false;
        }

        // Return only necessary fields
        if (Arr::has($data, Utils::getTenantModelForeignKey())) {
            return Arr::only($data, ['name', 'guard_name', 'is_system', Utils::getTenantModelForeignKey(), '_processed_permissions']);
        }

        return Arr::only($data, ['name', 'guard_name', 'is_system', '_processed_permissions']);
    }

    /**
     * Sync permissions to role after save.
     */
    public function syncPermissionsAfterSave(Role $role, array $processedData): void
    {
        if (! isset($processedData['_processed_permissions'])) {
            return;
        }

        $permissions = $processedData['_processed_permissions'];
        $permissionModels = collect();

        $permissions->each(function ($permission) use ($permissionModels, $processedData) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                'name' => $permission,
                'guard_name' => $processedData['guard_name'],
            ]));
        });

        $role->syncPermissions($permissionModels);
    }

    /**
     * Handle creation of new permissions from form data.
     */
    private function handleNewPermissions(array $data): Collection
    {
        if (! isset($data['new_permissions']) || ! is_array($data['new_permissions'])) {
            return collect();
        }

        return $this->permissionService->createNewPermissions($data['new_permissions']);
    }
}
