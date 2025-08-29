<?php

namespace App\Services;

use App\Enums\Permissions\PermissionContextEnum;
use App\Enums\Permissions\PermissionNounEnum;
use App\Enums\Permissions\PermissionVerbEnum;
use App\Exceptions\Repositories\RepositoryException;
use App\Exceptions\Services\PermissionServiceException;
use App\Exceptions\Services\ServiceException;
use App\Libs\Permissions\PermissionHelper;
use App\Models\Permission;
use App\Repositories\Interfaces\PermissionRepositoryInterface;
use App\Services\Interfaces\PermissionServiceInterface;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

/**
 * Permission service implementation.
 *
 * Handles permission-related business logic operations.
 *
 * @throws ServiceException When permission service operations fail
 * @throws RepositoryException When repository operations fail
 */
class PermissionService extends BaseService implements PermissionServiceInterface
{
    /**
     * PermissionService constructor.
     */
    public function __construct(
        private PermissionRepositoryInterface $permissionRepository
    ) {
        parent::__construct($permissionRepository);
    }

    /**
     * Get all non-system permissions.
     *
     * @throws RepositoryException When database error occurs
     */
    public function getAllNonSystemPermissions(): Collection
    {
        try {
            return $this->permissionRepository->getAllNonSystemPermissions();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to retrieve non-system permissions: '.$e->getMessage());
        }
    }

    /**
     * Get permissions by guard name (non-system only).
     *
     * @throws RepositoryException When database error occurs
     */
    public function getByGuardName(string $guardName): Collection
    {
        try {
            return $this->permissionRepository->getByGuardName($guardName);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to retrieve permissions by guard name: '.$e->getMessage());
        }
    }

    /**
     * Check if permission exists by name (non-system only).
     *
     * @throws RepositoryException When database error occurs
     */
    public function existsByName(string $name, ?string $guardName = null): bool
    {
        try {
            return $this->permissionRepository->existsByName($name, $guardName);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to check permission existence: '.$e->getMessage());
        }
    }

    /**
     * Create a new permission.
     *
     * @throws PermissionServiceException When permission creation fails
     * @throws RepositoryException When repository operations fail
     */
    public function createPermission(array $data): Permission
    {
        try {
            // Validate input data
            $this->validatePermissionData($data);

            return DB::transaction(function () use ($data) {
                // Create permission with is_system = false (handled by repository)
                return $this->repository->create($data);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (PermissionServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw PermissionServiceException::permissionCreationFailed(
                $data['name'] ?? null,
                $e->getMessage()
            );
        }
    }

    /**
     * Update a permission.
     *
     * @throws PermissionServiceException When permission update fails
     * @throws RepositoryException When repository operations fail
     */
    public function updatePermission(string $id, array $data): Permission
    {
        try {
            // Validate input data
            $this->validatePermissionData($data, $id);

            return DB::transaction(function () use ($id, $data) {
                return $this->repository->updateById($id, $data);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (PermissionServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw PermissionServiceException::permissionUpdateFailed(
                $data['name'] ?? null,
                $e->getMessage()
            );
        }
    }

    /**
     * Delete a permission.
     *
     * @throws PermissionServiceException When permission deletion fails
     * @throws RepositoryException When repository operations fail
     */
    public function deletePermission(string $id): bool
    {
        try {
            return DB::transaction(function () use ($id) {
                return $this->repository->deleteById($id);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            $permission = $this->repository->findById($id);
            throw PermissionServiceException::permissionDeletionFailed(
                $permission ? $permission->name : null,
                $e->getMessage()
            );
        }
    }

    /**
     * Validate permission data.
     *
     * @param  string|null  $permissionId  For update validation
     *
     * @throws PermissionServiceException When validation fails
     */
    private function validatePermissionData(array $data, ?string $permissionId = null): void
    {
        $rules = [
            'name' => 'required|string|max:255',
            'guard_name' => 'nullable|string|max:255',
        ];

        // Add unique rule for name
        if ($permissionId) {
            $rules['name'] .= '|unique:permissions,name,'.$permissionId;
        } else {
            $rules['name'] .= '|unique:permissions,name';
        }

        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw PermissionServiceException::permissionNameValidationFailed(
                $data['name'] ?? null,
                implode(', ', $validator->errors()->all())
            );
        }
    }

    /**
     * Get permissions for dropdown/select options (non-system only).
     *
     * @throws RepositoryException When database error occurs
     */
    public function getPermissionOptions(): array
    {
        try {
            return $this->getAllNonSystemPermissions()
                ->pluck('name', 'id')
                ->toArray();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get permission options: '.$e->getMessage());
        }
    }

    /**
     * Create new permissions from form data.
     *
     * @param  array  $newPermissions  Array of permission data
     * @return Collection Collection of created permission names
     *
     * @throws ServiceException When permission creation fails
     */
    public function createNewPermissions(array $newPermissions): Collection
    {
        $createdPermissions = collect();

        if (empty($newPermissions) || ! is_array($newPermissions)) {
            return $createdPermissions;
        }

        try {
            DB::transaction(function () use ($newPermissions, $createdPermissions) {
                foreach ($newPermissions as $newPermission) {
                    $permissionName = $this->createSinglePermission($newPermission);
                    if ($permissionName) {
                        $createdPermissions->push($permissionName);
                    }
                }
            });
        } catch (Exception $e) {
            Log::error('Failed to create new permissions', [
                'error' => $e->getMessage(),
                'permissions' => $newPermissions,
            ]);
            throw ServiceException::operationFailed('Failed to create new permissions: '.$e->getMessage());
        }

        return $createdPermissions;
    }

    /**
     * Create a single permission from permission data.
     *
     * @return string|null Permission name if created successfully
     */
    private function createSinglePermission(array $permissionData): ?string
    {
        if (! isset($permissionData['verb'], $permissionData['noun'], $permissionData['context'])) {
            return null;
        }

        try {
            $builder = PermissionHelper::make();

            // Set verb
            $verbEnum = PermissionVerbEnum::from($permissionData['verb']);
            $builder->verb($verbEnum);

            // Set noun
            $nounEnum = PermissionNounEnum::from($permissionData['noun']);
            $builder->noun($nounEnum);

            // Set context
            $contextEnum = PermissionContextEnum::from($permissionData['context']);
            $builder->context($contextEnum);

            // Add attribute value if needed
            if (in_array($permissionData['context'], [PermissionContextEnum::ID->value, PermissionContextEnum::TAG->value])
                && ! empty($permissionData['attribute_value'])) {
                $builder->withAttribute($permissionData['attribute_value']);
            }

            $permissionName = $builder->build();
            $guardName = $permissionData['guard_name'] ?? 'web';

            // Create the permission if it doesn't exist
            Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guardName,
            ], [
                'is_system' => false,
            ]);

            return $permissionName;
        } catch (Exception $e) {
            Log::warning('Failed to create single permission', [
                'error' => $e->getMessage(),
                'permission_data' => $permissionData,
            ]);

            return null;
        }
    }

    /**
     * Get existing custom permissions (non-system permissions).
     *
     * @throws ServiceException When retrieval fails
     */
    public function getExistingCustomPermissions(): Collection
    {
        try {
            return Permission::where('is_system', false)
                ->pluck('name')
                ->sort()
                ->values();
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to retrieve custom permissions: '.$e->getMessage());
        }
    }

    /**
     * Ensure a permission exists, create it if it doesn't.
     *
     * @param  string  $permissionName  The permission name to ensure exists
     * @param  string|null  $guardName  The guard name (defaults to 'web')
     * @return Permission The existing or newly created permission
     *
     * @throws ServiceException When permission creation fails
     */
    public function ensurePermissionExists(string $permissionName, ?string $guardName = null): Permission
    {
        try {
            $guardName = $guardName ?? 'web';

            // Check if permission already exists
            $permission = Permission::where('name', $permissionName)
                ->where('guard_name', $guardName)
                ->first();

            if ($permission) {
                return $permission;
            }

            // Create the permission if it doesn't exist
            return Permission::create([
                'name' => $permissionName,
                'guard_name' => $guardName,
                'is_system' => false,
            ]);
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to ensure permission exists: '.$e->getMessage());
        }
    }

    /**
     * Validate permission data structure.
     */
    public function validatePermissionStructure(array $permissionData): bool
    {
        $requiredFields = ['verb', 'noun', 'context'];

        foreach ($requiredFields as $field) {
            if (! isset($permissionData[$field]) || empty($permissionData[$field])) {
                return false;
            }
        }

        // Validate enum values
        try {
            PermissionVerbEnum::from($permissionData['verb']);
            PermissionNounEnum::from($permissionData['noun']);
            PermissionContextEnum::from($permissionData['context']);
        } catch (Exception $e) {
            return false;
        }

        return true;
    }

    /**
     * Override the base all method to return non-system permissions only.
     *
     * @throws RepositoryException When database error occurs
     */
    public function all(): Collection
    {
        try {
            return $this->getAllNonSystemPermissions();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to retrieve all permissions: '.$e->getMessage());
        }
    }
}
