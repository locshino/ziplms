<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Exception Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used for various exception messages
    | that are thrown throughout the application. You are free to modify
    | these language lines according to your application's requirements.
    |
    */

    // Base Application Exception
    'application_error' => 'An application error occurred.',
    'application_error_with_reason' => 'An application error occurred: :reason',

    // Repository Exceptions
    'repositories' => [
        'resource_not_found' => 'The requested resource was not found.',
        'resource_not_found_with_id' => 'Resource with ID ":id" was not found.',
        'resource_not_found_with_reason' => 'Resource not found: :reason',
        
        'create_failed' => 'Failed to create the resource.',
        'create_failed_with_reason' => 'Failed to create resource: :reason',
        
        'update_failed' => 'Failed to update the resource.',
        'update_failed_with_id' => 'Failed to update resource with ID ":id".',
        'update_failed_with_reason' => 'Failed to update resource: :reason',
        
        'delete_failed' => 'Failed to delete the resource.',
        'delete_failed_with_id' => 'Failed to delete resource with ID ":id".',
        'delete_failed_with_reason' => 'Failed to delete resource: :reason',
        
        'validation_failed' => 'Data validation failed.',
        'validation_failed_with_reason' => 'Validation failed: :reason',
        
        'database_error' => 'A database error occurred.',
        'database_error_with_reason' => 'Database error: :reason',
        
        'duplicate_entry' => 'A duplicate entry was detected.',
        'duplicate_entry_with_reason' => 'Duplicate entry: :reason',
        
        'resource_in_use' => 'The resource is currently in use and cannot be modified.',
        'resource_in_use_with_reason' => 'Resource in use: :reason',
    ],

    // Service Exceptions
    'services' => [
        'service_error' => 'A service error occurred.',
        'service_error_with_reason' => 'Service error: :reason',
        
        'business_logic_error' => 'A business logic error occurred.',
        'business_logic_error_with_reason' => 'Business logic error: :reason',
        
        'operation_failed' => 'The operation failed to complete.',
        'operation_failed_with_reason' => 'Operation failed: :reason',
        
        'operation_not_permitted' => 'The operation is not permitted.',
        'operation_not_permitted_with_reason' => 'Operation not permitted: :reason',
        
        'invalid_input' => 'The provided input is invalid.',
        'invalid_input_with_reason' => 'Invalid input: :reason',
        
        'validation_error' => 'Input validation failed.',
        'validation_error_with_reason' => 'Validation error: :reason',
        
        'authorization_failed' => 'Authorization failed.',
        'authorization_failed_with_reason' => 'Authorization failed: :reason',
        
        'resource_conflict' => 'A resource conflict occurred.',
        'resource_conflict_with_reason' => 'Resource conflict: :reason',
        
        'dependency_error' => 'A dependency error occurred.',
        'dependency_error_with_reason' => 'Dependency error: :reason',
        
        'transaction_failed' => 'The database transaction failed.',
        'transaction_failed_with_reason' => 'Transaction failed: :reason',
        
        'external_service_error' => 'An external service error occurred.',
        'external_service_error_with_reason' => 'External service error: :reason',
        
        'configuration_error' => 'A configuration error occurred.',
        'configuration_error_with_reason' => 'Configuration error: :reason',
        
        'timeout_error' => 'The operation timed out.',
        'timeout_error_with_reason' => 'Timeout error: :reason',
        
        'rate_limit_exceeded' => 'Rate limit exceeded.',
        'rate_limit_exceeded_with_reason' => 'Rate limit exceeded: :reason',
        
        'quota_exceeded' => 'Quota exceeded.',
        'quota_exceeded_with_reason' => 'Quota exceeded: :reason',
        
        'service_unavailable' => 'The service is currently unavailable.',
        'service_unavailable_with_reason' => 'Service unavailable: :reason',
        
        'maintenance_mode' => 'The system is currently in maintenance mode.',
        'maintenance_mode_with_reason' => 'Maintenance mode: :reason',
        
        'feature_disabled' => 'This feature is currently disabled.',
        'feature_disabled_with_reason' => 'Feature disabled: :reason',
        
        'insufficient_data' => 'Insufficient data to complete the operation.',
        'insufficient_data_with_reason' => 'Insufficient data: :reason',
        
        'data_integrity_error' => 'A data integrity error occurred.',
        'data_integrity_error_with_reason' => 'Data integrity error: :reason',
    ],
];