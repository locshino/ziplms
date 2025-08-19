<?php

namespace App\Exceptions\Repositories;

/**
 * Exception for permission repository-related errors.
 *
 * This exception class provides specific error handling for permission repository operations,
 * including permission-specific validation errors and permission management issues.
 *
 * @throws PermissionRepositoryException When permission repository operations fail
 */
class PermissionRepositoryException extends RepositoryException
{
    /**
     * Create exception for permission name already exists.
     *
     * @param  string|null  $permissionName  The permission name
     */
    public static function permissionNameExists(?string $permissionName = null): static
    {
        $key = $permissionName
            ? 'exceptions_repositories.permission_name_exists_with_name'
            : 'exceptions_repositories.permission_name_exists';

        return new static($key, ['permission_name' => $permissionName]);
    }

    /**
     * Create exception for permission in use by roles.
     *
     * @param  string|null  $permissionName  The permission name
     * @param  int|null  $roleCount  The number of roles using this permission
     */
    public static function permissionInUseByRoles(?string $permissionName = null, ?int $roleCount = null): static
    {
        if ($permissionName && $roleCount) {
            $key = 'exceptions_repositories.permission_in_use_by_roles_with_name_and_count';
            $replace = ['permission_name' => $permissionName, 'role_count' => $roleCount];
        } elseif ($permissionName) {
            $key = 'exceptions_repositories.permission_in_use_by_roles_with_name';
            $replace = ['permission_name' => $permissionName];
        } else {
            $key = 'exceptions_repositories.permission_in_use_by_roles';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for invalid guard name.
     *
     * @param  string|null  $guardName  The guard name
     */
    public static function invalidGuardName(?string $guardName = null): static
    {
        $key = $guardName
            ? 'exceptions_repositories.invalid_guard_name_with_name'
            : 'exceptions_repositories.invalid_guard_name';

        return new static($key, ['guard_name' => $guardName]);
    }

    /**
     * Create exception for permission group not found.
     *
     * @param  string|null  $groupName  The group name
     */
    public static function permissionGroupNotFound(?string $groupName = null): static
    {
        $key = $groupName
            ? 'exceptions_repositories.permission_group_not_found_with_name'
            : 'exceptions_repositories.permission_group_not_found';

        return new static($key, ['group_name' => $groupName]);
    }

    /**
     * Create exception for invalid permission format.
     *
     * @param  string|null  $permissionName  The permission name
     * @param  string|null  $expectedFormat  The expected format
     */
    public static function invalidPermissionFormat(?string $permissionName = null, ?string $expectedFormat = null): static
    {
        if ($permissionName && $expectedFormat) {
            $key = 'exceptions_repositories.invalid_permission_format_with_name_and_format';
            $replace = ['permission_name' => $permissionName, 'expected_format' => $expectedFormat];
        } elseif ($permissionName) {
            $key = 'exceptions_repositories.invalid_permission_format_with_name';
            $replace = ['permission_name' => $permissionName];
        } else {
            $key = 'exceptions_repositories.invalid_permission_format';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for permission sync failed.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function permissionSyncFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories.permission_sync_failed_with_reason'
            : 'exceptions_repositories.permission_sync_failed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for general operation failure.
     *
     * @param  string|null  $operation  The operation that failed
     * @param  string|null  $reason  The failure reason
     */
    public static function operationFailed(?string $operation = null, ?string $reason = null): static
    {
        if ($operation && $reason) {
            $key = 'exceptions_repositories.operation_failed_with_operation_and_reason';
            $replace = ['operation' => $operation, 'reason' => $reason];
        } elseif ($operation) {
            $key = 'exceptions_repositories.operation_failed_with_operation';
            $replace = ['operation' => $operation];
        } elseif ($reason) {
            $key = 'exceptions_repositories.operation_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_repositories.operation_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }
}
