<?php

namespace App\Exceptions\Services;

use Exception;

/**
 * Base exception for service-related errors.
 * 
 * This exception class provides localized error messages for service operations.
 * All service-specific exceptions should extend this class.
 * 
 * @throws ServiceException When service operations fail
 */
class ServiceException extends Exception
{
    /**
     * Create a new service exception with localized message.
     *
     * @param string $key The language key for the error message
     * @param array $replace Parameters to replace in the message
     * @param int $code The exception code
     * @param Exception|null $previous The previous exception
     */
    public function __construct(
        string $key = 'exceptions_services.service_error',
        array $replace = [],
        int $code = 0,
        ?Exception $previous = null
    ) {
        $message = __($key, $replace);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create exception for business logic error.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function businessLogicError(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.business_logic_error_with_reason'
            : 'exceptions_services.business_logic_error';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for operation failed.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function operationFailed(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.operation_failed_with_reason'
            : 'exceptions_services.operation_failed';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for operation not permitted.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function operationNotPermitted(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.operation_not_permitted_with_reason'
            : 'exceptions_services.operation_not_permitted';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for invalid input.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function invalidInput(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.invalid_input_with_reason'
            : 'exceptions_services.invalid_input';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for validation error.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function validationError(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.validation_error_with_reason'
            : 'exceptions_services.validation_error';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for authorization failed.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function authorizationFailed(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.authorization_failed_with_reason'
            : 'exceptions_services.authorization_failed';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for resource conflict.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function resourceConflict(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.resource_conflict_with_reason'
            : 'exceptions_services.resource_conflict';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for dependency error.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function dependencyError(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.dependency_error_with_reason'
            : 'exceptions_services.dependency_error';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for transaction failed.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function transactionFailed(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.transaction_failed_with_reason'
            : 'exceptions_services.transaction_failed';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for external service error.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function externalServiceError(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.external_service_error_with_reason'
            : 'exceptions_services.external_service_error';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for configuration error.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function configurationError(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.configuration_error_with_reason'
            : 'exceptions_services.configuration_error';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for timeout error.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function timeoutError(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.timeout_error_with_reason'
            : 'exceptions_services.timeout_error';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for rate limit exceeded.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function rateLimitExceeded(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.rate_limit_exceeded_with_reason'
            : 'exceptions_services.rate_limit_exceeded';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for quota exceeded.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function quotaExceeded(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.quota_exceeded_with_reason'
            : 'exceptions_services.quota_exceeded';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for service unavailable.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function serviceUnavailable(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.service_unavailable_with_reason'
            : 'exceptions_services.service_unavailable';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for maintenance mode.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function maintenanceMode(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.maintenance_mode_with_reason'
            : 'exceptions_services.maintenance_mode';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for feature disabled.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function featureDisabled(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.feature_disabled_with_reason'
            : 'exceptions_services.feature_disabled';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for insufficient data.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function insufficientData(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.insufficient_data_with_reason'
            : 'exceptions_services.insufficient_data';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for data integrity error.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function dataIntegrityError(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_services.data_integrity_error_with_reason'
            : 'exceptions_services.data_integrity_error';
        
        return new static($key, ['reason' => $reason]);
    }
}