<?php

namespace App\Exceptions\Repositories;

use App\Exceptions\ApplicationException;
use App\Enums\HttpStatusCode;
use Exception;

/**
 * Base exception for repository-related errors.
 *
 * This exception class provides localized error messages for repository operations
 * with HTTP status code support. All repository-specific exceptions should extend this class.
 *
 * @throws RepositoryException When repository operations fail
 */
class RepositoryException extends ApplicationException
{
    /**
     * The default language key for repository exceptions.
     *
     * @var string
     */
    protected static string $defaultKey = 'exceptions.repositories.resource_not_found';

    /**
     * The default HTTP status code for repository exceptions.
     *
     * @var HttpStatusCode
     */
    protected HttpStatusCode $httpStatusCode;

    /**
     * Create a new repository exception with localized message.
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
     * Create exception for resource not found.
     *
     * @param  string|null  $id  The resource ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function notFound(?string $id = null, ?string $reason = null): static
    {
        if ($reason) {
            $key = 'exceptions.repositories.resource_not_found_with_reason';
            $replace = ['reason' => $reason];
        } elseif ($id) {
            $key = 'exceptions.repositories.resource_not_found_with_id';
            $replace = ['id' => $id];
        } else {
            $key = 'exceptions.repositories.resource_not_found';
            $replace = [];
        }

        return new static($key, $replace, HttpStatusCode::NOT_FOUND);
    }

    /**
     * Create exception for create operation failure.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function createFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.create_failed_with_reason'
            : 'exceptions.repositories.create_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for update operation failure.
     *
     * @param  string|null  $id  The resource ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function updateFailed(?string $id = null, ?string $reason = null): static
    {
        if ($reason) {
            $key = 'exceptions.repositories.update_failed_with_reason';
            $replace = ['reason' => $reason];
        } elseif ($id) {
            $key = 'exceptions.repositories.update_failed_with_id';
            $replace = ['id' => $id];
        } else {
            $key = 'exceptions.repositories.update_failed';
            $replace = [];
        }

        return new static($key, $replace, HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for delete operation failure.
     *
     * @param  string|null  $id  The resource ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function deleteFailed(?string $id = null, ?string $reason = null): static
    {
        if ($reason) {
            $key = 'exceptions.repositories.delete_failed_with_reason';
            $replace = ['reason' => $reason];
        } elseif ($id) {
            $key = 'exceptions.repositories.delete_failed_with_id';
            $replace = ['id' => $id];
        } else {
            $key = 'exceptions.repositories.delete_failed';
            $replace = [];
        }

        return new static($key, $replace, HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for validation failure.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function validationFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.validation_failed_with_reason'
            : 'exceptions.repositories.validation_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::UNPROCESSABLE_ENTITY);
    }

    /**
     * Create exception for database error.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function databaseError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.database_error_with_reason'
            : 'exceptions.repositories.database_error';

        return new static($key, ['reason' => $reason], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * Create exception for duplicate entry.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function duplicateEntry(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.duplicate_entry_with_reason'
            : 'exceptions.repositories.duplicate_entry';

        return new static($key, ['reason' => $reason], HttpStatusCode::CONFLICT);
    }

    /**
     * Create exception for resource in use.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function resourceInUse(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.resource_in_use_with_reason'
            : 'exceptions.repositories.resource_in_use';

        return new static($key, ['reason' => $reason], HttpStatusCode::CONFLICT);
    }
}
