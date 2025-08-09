<?php

namespace App\Exceptions\Repositories;

/**
 * Exception for role repository-related errors.
 *
 * This exception class provides specific error handling for role repository operations,
 * including system role protection and role-specific validation errors.
 *
 * @throws RoleRepositoryException When role repository operations fail
 */
class RoleRepositoryException extends RepositoryException
{
    /**
     * Create exception for system role protection.
     *
     * @param  string|null  $operation  The attempted operation
     * @param  string|null  $roleName  The role name
     */
    public static function systemRoleProtected(?string $operation = null, ?string $roleName = null): static
    {
        if ($operation && $roleName) {
            $key = 'exceptions_repositories.system_role_protected_with_operation_and_name';
            $replace = ['operation' => $operation, 'role_name' => $roleName];
        } elseif ($operation) {
            $key = 'exceptions_repositories.system_role_protected_with_operation';
            $replace = ['operation' => $operation];
        } elseif ($roleName) {
            $key = 'exceptions_repositories.system_role_protected_with_name';
            $replace = ['role_name' => $roleName];
        } else {
            $key = 'exceptions_repositories.system_role_protected';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for role name already exists.
     *
     * @param  string|null  $roleName  The role name
     */
    public static function roleNameExists(?string $roleName = null): static
    {
        $key = $roleName
            ? 'exceptions_repositories.role_name_exists_with_name'
            : 'exceptions_repositories.role_name_exists';

        return new static($key, ['role_name' => $roleName]);
    }

    /**
     * Create exception for role has users.
     *
     * @param  string|null  $roleName  The role name
     * @param  int|null  $userCount  The number of users
     */
    public static function roleHasUsers(?string $roleName = null, ?int $userCount = null): static
    {
        if ($roleName && $userCount) {
            $key = 'exceptions_repositories.role_has_users_with_name_and_count';
            $replace = ['role_name' => $roleName, 'user_count' => $userCount];
        } elseif ($roleName) {
            $key = 'exceptions_repositories.role_has_users_with_name';
            $replace = ['role_name' => $roleName];
        } else {
            $key = 'exceptions_repositories.role_has_users';
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
     * Create exception for role permission sync failed.
     *
     * @param  string|null  $roleName  The role name
     * @param  string|null  $reason  The failure reason
     */
    public static function permissionSyncFailed(?string $roleName = null, ?string $reason = null): static
    {
        if ($roleName && $reason) {
            $key = 'exceptions_repositories.permission_sync_failed_with_name_and_reason';
            $replace = ['role_name' => $roleName, 'reason' => $reason];
        } elseif ($roleName) {
            $key = 'exceptions_repositories.permission_sync_failed_with_name';
            $replace = ['role_name' => $roleName];
        } elseif ($reason) {
            $key = 'exceptions_repositories.permission_sync_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_repositories.permission_sync_failed';
            $replace = [];
        }

        return new static($key, $replace);
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