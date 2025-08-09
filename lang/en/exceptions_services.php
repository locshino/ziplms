<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Service Exception Messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for service exception messages
    | that are thrown by various service classes throughout the application.
    |
    */

    'service_error' => 'A service error occurred.',
    'business_logic_error' => 'Business logic validation failed.',
    'operation_failed' => 'The requested operation failed.',
    'operation_not_permitted' => 'Operation not permitted.',
    'invalid_input' => 'Invalid input provided.',
    'validation_error' => 'Data validation failed.',
    'authorization_failed' => 'Authorization failed for this operation.',
    'resource_conflict' => 'Resource conflict detected.',
    'dependency_error' => 'Dependency requirement not met.',
    'transaction_failed' => 'Database transaction failed.',
    'external_service_error' => 'External service error occurred.',
    'configuration_error' => 'Service configuration error.',
    'timeout_error' => 'Operation timeout occurred.',
    'rate_limit_exceeded' => 'Rate limit exceeded.',
    'quota_exceeded' => 'Quota limit exceeded.',
    'service_unavailable' => 'Service is currently unavailable.',
    'maintenance_mode' => 'Service is in maintenance mode.',
    'feature_disabled' => 'This feature is currently disabled.',
    'insufficient_data' => 'Insufficient data to complete operation.',
    'data_integrity_error' => 'Data integrity violation detected.',

    // Role Service Exceptions
    'system_role_modification_attempt' => 'Attempted to modify a system role.',
    'system_role_modification_attempt_with_operation' => 'Attempted to :operation a system role.',
    'system_role_modification_attempt_with_name' => 'Attempted to modify system role ":role_name".',
    'system_role_modification_attempt_with_operation_and_name' => 'Attempted to :operation system role ":role_name".',
    'role_creation_failed' => 'Failed to create role.',
    'role_creation_failed_with_name' => 'Failed to create role ":role_name".',
    'role_creation_failed_with_reason' => 'Failed to create role: :reason',
    'role_creation_failed_with_name_and_reason' => 'Failed to create role ":role_name": :reason',
    'role_update_failed' => 'Failed to update role.',
    'role_update_failed_with_name' => 'Failed to update role ":role_name".',
    'role_update_failed_with_reason' => 'Failed to update role: :reason',
    'role_update_failed_with_name_and_reason' => 'Failed to update role ":role_name": :reason',
    'role_deletion_failed' => 'Failed to delete role.',
    'role_deletion_failed_with_name' => 'Failed to delete role ":role_name".',
    'role_deletion_failed_with_reason' => 'Failed to delete role: :reason',
    'role_deletion_failed_with_name_and_reason' => 'Failed to delete role ":role_name": :reason',
    'role_name_validation_failed' => 'Role name validation failed.',
    'role_name_validation_failed_with_name' => 'Role name ":role_name" validation failed.',
    'role_name_validation_failed_with_reason' => 'Role name validation failed: :reason',
    'role_name_validation_failed_with_name_and_reason' => 'Role name ":role_name" validation failed: :reason',
    'role_permission_assignment_failed' => 'Failed to assign permissions to role.',
    'role_permission_assignment_failed_with_name' => 'Failed to assign permissions to role ":role_name".',
    'role_permission_assignment_failed_with_reason' => 'Failed to assign permissions to role: :reason',
    'role_permission_assignment_failed_with_name_and_reason' => 'Failed to assign permissions to role ":role_name": :reason',

    // Permission Service Exceptions
    'permission_creation_failed' => 'Failed to create permission.',
    'permission_creation_failed_with_name' => 'Failed to create permission ":permission_name".',
    'permission_creation_failed_with_reason' => 'Failed to create permission: :reason',
    'permission_creation_failed_with_name_and_reason' => 'Failed to create permission ":permission_name": :reason',
    'permission_update_failed' => 'Failed to update permission.',
    'permission_update_failed_with_name' => 'Failed to update permission ":permission_name".',
    'permission_update_failed_with_reason' => 'Failed to update permission: :reason',
    'permission_update_failed_with_name_and_reason' => 'Failed to update permission ":permission_name": :reason',
    'permission_deletion_failed' => 'Failed to delete permission.',
    'permission_deletion_failed_with_name' => 'Failed to delete permission ":permission_name".',
    'permission_deletion_failed_with_reason' => 'Failed to delete permission: :reason',
    'permission_deletion_failed_with_name_and_reason' => 'Failed to delete permission ":permission_name": :reason',
    'permission_name_validation_failed' => 'Permission name validation failed.',
    'permission_name_validation_failed_with_name' => 'Permission name ":permission_name" validation failed.',
    'permission_name_validation_failed_with_reason' => 'Permission name validation failed: :reason',
    'permission_name_validation_failed_with_name_and_reason' => 'Permission name ":permission_name" validation failed: :reason',
    'permission_sync_failed' => 'Failed to sync permissions.',
    'permission_sync_failed_with_reason' => 'Failed to sync permissions: :reason',
    'permission_group_operation_failed' => 'Permission group operation failed.',
    'permission_group_operation_failed_with_group' => 'Permission group ":group_name" operation failed.',
    'permission_group_operation_failed_with_group_and_operation' => 'Permission group ":group_name" :operation operation failed.',
    'permission_group_operation_failed_with_group_operation_and_reason' => 'Permission group ":group_name" :operation operation failed: :reason',
    'invalid_permission_format' => 'Invalid permission format provided.',
    'invalid_permission_format_with_name' => 'Invalid permission format for ":permission_name".',
    'invalid_permission_format_with_name_and_format' => 'Invalid permission format for ":permission_name". Expected format: :expected_format',
];
