<?php

namespace App\Exceptions\Services;

/**
 * Exception for role service-related errors.
 *
 * This exception class provides specific error handling for role service operations,
 * including role business logic validation and role management operations.
 *
 * @throws RoleServiceException When role service operations fail
 */
class RoleServiceException extends ServiceException
{
    /**
     * Create exception for system role modification attempt.
     *
     * @param  string|null  $operation  The attempted operation
     * @param  string|null  $roleName  The role name
     */
    public static function systemRoleModificationAttempt(?string $operation = null, ?string $roleName = null): static
    {
        if ($operation && $roleName) {
            $key = 'exceptions_services.system_role_modification_attempt_with_operation_and_name';
            $replace = ['operation' => $operation, 'role_name' => $roleName];
        } elseif ($operation) {
            $key = 'exceptions_services.system_role_modification_attempt_with_operation';
            $replace = ['operation' => $operation];
        } elseif ($roleName) {
            $key = 'exceptions_services.system_role_modification_attempt_with_name';
            $replace = ['role_name' => $roleName];
        } else {
            $key = 'exceptions_services.system_role_modification_attempt';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for role creation failed.
     *
     * @param  string|null  $roleName  The role name
     * @param  string|null  $reason  The failure reason
     */
    public static function roleCreationFailed(?string $roleName = null, ?string $reason = null): static
    {
        if ($roleName && $reason) {
            $key = 'exceptions_services.role_creation_failed_with_name_and_reason';
            $replace = ['role_name' => $roleName, 'reason' => $reason];
        } elseif ($roleName) {
            $key = 'exceptions_services.role_creation_failed_with_name';
            $replace = ['role_name' => $roleName];
        } elseif ($reason) {
            $key = 'exceptions_services.role_creation_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.role_creation_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for role update failed.
     *
     * @param  string|null  $roleName  The role name
     * @param  string|null  $reason  The failure reason
     */
    public static function roleUpdateFailed(?string $roleName = null, ?string $reason = null): static
    {
        if ($roleName && $reason) {
            $key = 'exceptions_services.role_update_failed_with_name_and_reason';
            $replace = ['role_name' => $roleName, 'reason' => $reason];
        } elseif ($roleName) {
            $key = 'exceptions_services.role_update_failed_with_name';
            $replace = ['role_name' => $roleName];
        } elseif ($reason) {
            $key = 'exceptions_services.role_update_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.role_update_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for role deletion failed.
     *
     * @param  string|null  $roleName  The role name
     * @param  string|null  $reason  The failure reason
     */
    public static function roleDeletionFailed(?string $roleName = null, ?string $reason = null): static
    {
        if ($roleName && $reason) {
            $key = 'exceptions_services.role_deletion_failed_with_name_and_reason';
            $replace = ['role_name' => $roleName, 'reason' => $reason];
        } elseif ($roleName) {
            $key = 'exceptions_services.role_deletion_failed_with_name';
            $replace = ['role_name' => $roleName];
        } elseif ($reason) {
            $key = 'exceptions_services.role_deletion_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.role_deletion_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for role name validation failed.
     *
     * @param  string|null  $roleName  The role name
     * @param  string|null  $reason  The validation failure reason
     */
    public static function roleNameValidationFailed(?string $roleName = null, ?string $reason = null): static
    {
        if ($roleName && $reason) {
            $key = 'exceptions_services.role_name_validation_failed_with_name_and_reason';
            $replace = ['role_name' => $roleName, 'reason' => $reason];
        } elseif ($roleName) {
            $key = 'exceptions_services.role_name_validation_failed_with_name';
            $replace = ['role_name' => $roleName];
        } elseif ($reason) {
            $key = 'exceptions_services.role_name_validation_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.role_name_validation_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for role permission assignment failed.
     *
     * @param  string|null  $roleName  The role name
     * @param  string|null  $reason  The failure reason
     */
    public static function rolePermissionAssignmentFailed(?string $roleName = null, ?string $reason = null): static
    {
        if ($roleName && $reason) {
            $key = 'exceptions_services.role_permission_assignment_failed_with_name_and_reason';
            $replace = ['role_name' => $roleName, 'reason' => $reason];
        } elseif ($roleName) {
            $key = 'exceptions_services.role_permission_assignment_failed_with_name';
            $replace = ['role_name' => $roleName];
        } elseif ($reason) {
            $key = 'exceptions_services.role_permission_assignment_failed_with_reason';
            $replace = ['reason' => $reason];
        } else {
            $key = 'exceptions_services.role_permission_assignment_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }
}
