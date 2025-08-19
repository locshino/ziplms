<?php

namespace App\Exceptions\Services;

use App\Exceptions\ApplicationException;
use App\Enums\HttpStatusCode;
use Exception;

/**
 * Base exception for service-related errors.
 *
 * This exception class provides localized error messages for service operations
 * with HTTP status code support. All service-specific exceptions should extend this class.
 *
 * @throws ServiceException When service operations fail
 */
class ServiceException extends ApplicationException
{
    /**
     * The default language key for service exceptions.
     *
     * @var string
     */
    protected static string $defaultKey = 'exceptions.services.service_error';

    /**
     * The default HTTP status code for service exceptions.
     *
     * @var HttpStatusCode
     */
    protected HttpStatusCode $httpStatusCode;

    /**
     * Create a new service exception with localized message.
     *
     * @param  string|null  $key  The language key for the error message
     * @param  array  $replace  Parameters to replace in the message
     * @param  HttpStatusCode|int  $code  The HTTP status code or exception code
     * @param  Exception|null  $previous  The previous exception
     */
    public function __construct(
        ?string $key = null,
        array $replace = [],
        HttpStatusCode|int $code = HttpStatusCode::INTERNAL_SERVER_ERROR,
        ?Exception $previous = null
    ) {
        // Handle HttpStatusCode enum or integer code
        if ($code instanceof HttpStatusCode) {
            $this->httpStatusCode = $code;
            $intCode = $code->value;
        } else {
            $this->httpStatusCode = HttpStatusCode::INTERNAL_SERVER_ERROR;
            $intCode = $code;
        }

        parent::__construct($key, $replace, $intCode, $previous);
    }

    /**
     * Get the HTTP status code for this exception.
     *
     * @return HttpStatusCode
     */
    public function getHttpStatusCode(): HttpStatusCode
    {
        return $this->httpStatusCode;
    }

    /**
     * Create exception for business logic error.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function businessLogicError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.business_logic_error_with_reason'
            : 'exceptions.services.business_logic_error';

        return new static($key, ['reason' => $reason], HttpStatusCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * Create exception for operation failed.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function operationFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.operation_failed_with_reason'
            : 'exceptions.services.operation_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * Create exception for operation not permitted.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function operationNotPermitted(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.operation_not_permitted_with_reason'
            : 'exceptions.services.operation_not_permitted';

        return new static($key, ['reason' => $reason], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for invalid input.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function invalidInput(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.invalid_input_with_reason'
            : 'exceptions.services.invalid_input';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for validation error.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function validationError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.validation_error_with_reason'
            : 'exceptions.services.validation_error';

        return new static($key, ['reason' => $reason], HttpStatusCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * Create exception for authorization failed.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function authorizationFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.authorization_failed_with_reason'
            : 'exceptions.services.authorization_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::UNAUTHORIZED);
    }

    /**
     * Create exception for resource conflict.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function resourceConflict(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.resource_conflict_with_reason'
            : 'exceptions.services.resource_conflict';

        return new static($key, ['reason' => $reason], HttpStatusCode::CONFLICT);
    }

    /**
     * Create exception for dependency error.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function dependencyError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.dependency_error_with_reason'
            : 'exceptions.services.dependency_error';

        return new static($key, ['reason' => $reason], HttpStatusCode::FAILED_DEPENDENCY);
    }

    /**
     * Create exception for transaction failed.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function transactionFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.transaction_failed_with_reason'
            : 'exceptions.services.transaction_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * Create exception for external service error.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function externalServiceError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.external_service_error_with_reason'
            : 'exceptions.services.external_service_error';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_GATEWAY);
    }

    /**
     * Create exception for configuration error.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function configurationError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.configuration_error_with_reason'
            : 'exceptions.services.configuration_error';

        return new static($key, ['reason' => $reason], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * Create exception for timeout error.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function timeoutError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.timeout_error_with_reason'
            : 'exceptions.services.timeout_error';

        return new static($key, ['reason' => $reason], HttpStatusCode::REQUEST_TIMEOUT);
    }

    /**
     * Create exception for rate limit exceeded.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function rateLimitExceeded(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.rate_limit_exceeded_with_reason'
            : 'exceptions.services.rate_limit_exceeded';

        return new static($key, ['reason' => $reason], HttpStatusCode::TOO_MANY_REQUESTS);
    }

    /**
     * Create exception for quota exceeded.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function quotaExceeded(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.quota_exceeded_with_reason'
            : 'exceptions.services.quota_exceeded';

        return new static($key, ['reason' => $reason], HttpStatusCode::TOO_MANY_REQUESTS);
    }

    /**
     * Create exception for service unavailable.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function serviceUnavailable(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.service_unavailable_with_reason'
            : 'exceptions.services.service_unavailable';

        return new static($key, ['reason' => $reason], HttpStatusCode::SERVICE_UNAVAILABLE);
    }

    /**
     * Create exception for maintenance mode.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function maintenanceMode(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.maintenance_mode_with_reason'
            : 'exceptions.services.maintenance_mode';

        return new static($key, ['reason' => $reason], HttpStatusCode::SERVICE_UNAVAILABLE);
    }

    /**
     * Create exception for feature disabled.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function featureDisabled(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.feature_disabled_with_reason'
            : 'exceptions.services.feature_disabled';

        return new static($key, ['reason' => $reason], HttpStatusCode::NOT_IMPLEMENTED);
    }

    /**
     * Create exception for insufficient data.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function insufficientData(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.insufficient_data_with_reason'
            : 'exceptions.services.insufficient_data';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for data integrity error.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function dataIntegrityError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.services.data_integrity_error_with_reason'
            : 'exceptions.services.data_integrity_error';

        return new static($key, ['reason' => $reason], HttpStatusCode::UNPROCESSABLE_ENTITY);
    }
}
