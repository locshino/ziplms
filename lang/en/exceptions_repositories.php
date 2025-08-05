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
];