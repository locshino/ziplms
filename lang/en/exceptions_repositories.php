<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Repository Exception Messages
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for repository exception messages
    | that are thrown by various repository classes throughout the application.
    |
    */

    'resource_not_found' => 'The requested resource was not found.',
    'resource_not_found_with_id' => 'Resource with ID :id was not found.',
    'create_failed' => 'Failed to create the resource.',
    'create_failed_with_reason' => 'Failed to create resource: :reason',
    'update_failed' => 'Failed to update the resource.',
    'update_failed_with_id' => 'Failed to update resource with ID :id.',
    'update_failed_with_reason' => 'Failed to update resource: :reason',
    'delete_failed' => 'Failed to delete the resource.',
    'delete_failed_with_id' => 'Failed to delete resource with ID :id.',
    'delete_failed_with_reason' => 'Failed to delete resource: :reason',
    'validation_failed' => 'Validation failed for the provided data.',
    'database_error' => 'A database error occurred.',
    'constraint_violation' => 'Database constraint violation occurred.',
    'duplicate_entry' => 'Duplicate entry detected.',
    'foreign_key_constraint' => 'Foreign key constraint violation.',
    'invalid_data' => 'Invalid data provided.',
    'operation_not_allowed' => 'This operation is not allowed.',
    'resource_in_use' => 'Resource is currently in use and cannot be modified.',
    'insufficient_permissions' => 'Insufficient permissions to perform this operation.',

    // Role Repository Exceptions
    'system_role_protected' => 'System roles are protected and cannot be modified.',
    'system_role_protected_with_operation' => 'System roles are protected and cannot be :operation.',
    'system_role_protected_with_name' => 'System role ":role_name" is protected and cannot be modified.',
    'system_role_protected_with_operation_and_name' => 'System role ":role_name" is protected and cannot be :operation.',
    'role_name_exists' => 'A role with this name already exists.',
    'role_name_exists_with_name' => 'Role ":role_name" already exists.',
    'role_has_users' => 'This role has assigned users and cannot be deleted.',
    'role_has_users_with_name' => 'Role ":role_name" has assigned users and cannot be deleted.',
    'role_has_users_with_name_and_count' => 'Role ":role_name" has :user_count assigned users and cannot be deleted.',
    'invalid_guard_name' => 'Invalid guard name provided.',
    'invalid_guard_name_with_name' => 'Invalid guard name ":guard_name" provided.',
    'permission_sync_failed' => 'Failed to sync permissions.',
    'permission_sync_failed_with_name' => 'Failed to sync permissions for role ":role_name".',
    'permission_sync_failed_with_reason' => 'Failed to sync permissions: :reason',
    'permission_sync_failed_with_name_and_reason' => 'Failed to sync permissions for role ":role_name": :reason',

    // Permission Repository Exceptions
    'permission_name_exists' => 'A permission with this name already exists.',
    'permission_name_exists_with_name' => 'Permission ":permission_name" already exists.',
    'permission_in_use_by_roles' => 'This permission is assigned to roles and cannot be deleted.',
    'permission_in_use_by_roles_with_name' => 'Permission ":permission_name" is assigned to roles and cannot be deleted.',
    'permission_in_use_by_roles_with_name_and_count' => 'Permission ":permission_name" is assigned to :role_count roles and cannot be deleted.',
    'permission_group_not_found' => 'Permission group not found.',
    'permission_group_not_found_with_name' => 'Permission group ":group_name" not found.',
    'invalid_permission_format' => 'Invalid permission format.',
    'invalid_permission_format_with_name' => 'Invalid permission format for ":permission_name".',
    'invalid_permission_format_with_name_and_format' => 'Invalid permission format for ":permission_name". Expected format: :expected_format',
];
