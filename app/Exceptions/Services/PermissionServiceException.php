<?php

namespace App\Exceptions\Services;

/**
 * Exception for permission service-related errors.
 *
 * This exception class provides specific error handling for permission service operations,
 * including permission business logic validation and permission management operations.
 *
 * @throws PermissionServiceException When permission service operations fail
 */
class PermissionServiceException extends ServiceException
{
    /**
     * Create exception for permission creation failed.
     *
     * @param  string|null  $permissionName  The permission name
     * @param  string|null  $reason  The failure reason
     */
    public static function permissionCreationFailed(?string $permissionName = null, ?string $reason = null): static
    {
        if ($permissionName && $reason) {
            $key = 'exceptions_services.permission_creation_failed_with_name_and_reason';
            $replace = ['permission_name' => $permissionName, 'reason' => $reason];
        } elseif ($permissionName) {
            $key = 'exceptions_services.permission_creation_failed_with_name';
            $replace = ['permission_name' => $permissionName];
        } elseif ($reason) {
            $key = 'exceptions_services.permission_creation_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.permission_creation_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for permission update failed.
     *
     * @param  string|null  $permissionName  The permission name
     * @param  string|null  $reason  The failure reason
     */
    public static function permissionUpdateFailed(?string $permissionName = null, ?string $reason = null): static
    {
        if ($permissionName && $reason) {
            $key = 'exceptions_services.permission_update_failed_with_name_and_reason';
            $replace = ['permission_name' => $permissionName, 'reason' => $reason];
        } elseif ($permissionName) {
            $key = 'exceptions_services.permission_update_failed_with_name';
            $replace = ['permission_name' => $permissionName];
        } elseif ($reason) {
            $key = 'exceptions_services.permission_update_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.permission_update_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for permission deletion failed.
     *
     * @param  string|null  $permissionName  The permission name
     * @param  string|null  $reason  The failure reason
     */
    public static function permissionDeletionFailed(?string $permissionName = null, ?string $reason = null): static
    {
        if ($permissionName && $reason) {
            $key = 'exceptions_services.permission_deletion_failed_with_name_and_reason';
            $replace = ['permission_name' => $permissionName, 'reason' => $reason];
        } elseif ($permissionName) {
            $key = 'exceptions_services.permission_deletion_failed_with_name';
            $replace = ['permission_name' => $permissionName];
        } elseif ($reason) {
            $key = 'exceptions_services.permission_deletion_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.permission_deletion_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for permission name validation failed.
     *
     * @param  string|null  $permissionName  The permission name
     * @param  string|null  $reason  The validation failure reason
     */
    public static function permissionNameValidationFailed(?string $permissionName = null, ?string $reason = null): static
    {
        if ($permissionName && $reason) {
            $key = 'exceptions_services.permission_name_validation_failed_with_name_and_reason';
            $replace = ['permission_name' => $permissionName, 'reason' => $reason];
        } elseif ($permissionName) {
            $key = 'exceptions_services.permission_name_validation_failed_with_name';
            $replace = ['permission_name' => $permissionName];
        } elseif ($reason) {
            $key = 'exceptions_services.permission_name_validation_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.permission_name_validation_failed';
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
            ? 'exceptions_services.permission_sync_failed_with_reason'
            : 'exceptions_services.permission_sync_failed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for permission group operation failed.
     *
     * @param  string|null  $groupName  The group name
     * @param  string|null  $operation  The operation
     * @param  string|null  $reason  The failure reason
     */
    public static function permissionGroupOperationFailed(?string $groupName = null, ?string $operation = null, ?string $reason = null): static
    {
        if ($groupName && $operation && $reason) {
            $key = 'exceptions_services.permission_group_operation_failed_with_group_operation_and_reason';
            $replace = ['group_name' => $groupName, 'operation' => $operation, 'reason' => $reason];
        } elseif ($groupName && $operation) {
            $key = 'exceptions_services.permission_group_operation_failed_with_group_and_operation';
            $replace = ['group_name' => $groupName, 'operation' => $operation];
        } elseif ($groupName) {
            $key = 'exceptions_services.permission_group_operation_failed_with_group';
            $replace = ['group_name' => $groupName];
        } else {
            $key = 'exceptions_services.permission_group_operation_failed';
            $replace = [];
        }

        return new static($key, $replace);
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
            $key = 'exceptions_services.invalid_permission_format_with_name_and_format';
            $replace = ['permission_name' => $permissionName, 'expected_format' => $expectedFormat];
        } elseif ($permissionName) {
            $key = 'exceptions_services.invalid_permission_format_with_name';
            $replace = ['permission_name' => $permissionName];
        } else {
            $key = 'exceptions_services.invalid_permission_format';
            $replace = [];
        }

        return new static($key, $replace);
    }
}