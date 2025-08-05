<?php

namespace App\Exceptions\Repositories;

use Exception;

/**
 * Base exception for repository-related errors.
 *
 * This exception class provides localized error messages for repository operations.
 * All repository-specific exceptions should extend this class.
 *
 * @throws RepositoryException When repository operations fail
 */
class RepositoryException extends Exception
{
    /**
     * Create a new repository exception with localized message.
     *
     * @param  string  $key  The language key for the error message
     * @param  array  $replace  Parameters to replace in the message
     * @param  int  $code  The exception code
     * @param  Exception|null  $previous  The previous exception
     */
    public function __construct(
        string $key = 'exceptions_repositories.resource_not_found',
        array $replace = [],
        int $code = 0,
        ?Exception $previous = null
    ) {
        $message = __($key, $replace);
        parent::__construct($message, $code, $previous);
    }

    /**
     * Create exception for resource not found.
     *
     * @param  string|null  $id  The resource ID
     * @param  string|null  $reason  The failure reason
     */
    public static function notFound(?string $id = null, ?string $reason = null): static
    {
        if ($reason) {
            $key = 'exceptions_repositories.resource_not_found_with_reason';
            $replace = ['reason' => $reason];
        } elseif ($id) {
            $key = 'exceptions_repositories.resource_not_found_with_id';
            $replace = ['id' => $id];
        } else {
            $key = 'exceptions_repositories.resource_not_found';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for create operation failure.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function createFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories.create_failed_with_reason'
            : 'exceptions_repositories.create_failed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for update operation failure.
     *
     * @param  string|null  $id  The resource ID
     * @param  string|null  $reason  The failure reason
     */
    public static function updateFailed(?string $id = null, ?string $reason = null): static
    {
        if ($reason) {
            $key = 'exceptions_repositories.update_failed_with_reason';
            $replace = ['reason' => $reason];
        } elseif ($id) {
            $key = 'exceptions_repositories.update_failed_with_id';
            $replace = ['id' => $id];
        } else {
            $key = 'exceptions_repositories.update_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for delete operation failure.
     *
     * @param  string|null  $id  The resource ID
     * @param  string|null  $reason  The failure reason
     */
    public static function deleteFailed(?string $id = null, ?string $reason = null): static
    {
        if ($reason) {
            $key = 'exceptions_repositories.delete_failed_with_reason';
            $replace = ['reason' => $reason];
        } elseif ($id) {
            $key = 'exceptions_repositories.delete_failed_with_id';
            $replace = ['id' => $id];
        } else {
            $key = 'exceptions_repositories.delete_failed';
            $replace = [];
        }

        return new static($key, $replace);
    }

    /**
     * Create exception for validation failure.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function validationFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories.validation_failed_with_reason'
            : 'exceptions_repositories.validation_failed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for database error.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function databaseError(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories.database_error_with_reason'
            : 'exceptions_repositories.database_error';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for duplicate entry.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function duplicateEntry(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories.duplicate_entry_with_reason'
            : 'exceptions_repositories.duplicate_entry';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for resource in use.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function resourceInUse(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories.resource_in_use_with_reason'
            : 'exceptions_repositories.resource_in_use';

        return new static($key, ['reason' => $reason]);
    }
}
