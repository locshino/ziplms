<?php

namespace App\Exceptions;

use App\Enums\HttpStatusCode;
use Exception;

/**
 * Base application exception class.
 *
 * This abstract class provides a foundation for all application-specific exceptions
 * with built-in localization support and common utility methods.
 */
abstract class ApplicationException extends Exception
{
    /**
     * The default language key for this exception type.
     */
    protected static string $defaultKey = 'exceptions.application_error';

    /**
     * The language key used for this exception instance.
     */
    protected ?string $key = null;

    /**
     * The HTTP status code for this exception.
     */
    protected HttpStatusCode $httpStatusCode;

    /**
     * Create a new application exception with localized message.
     *
     * @param  string|null  $key  The language key for the error message (uses default if null)
     * @param  array  $replace  Parameters to replace in the message
     * @param  HttpStatusCode|int  $code  The HTTP status code or exception code
     * @param  Exception|null  $previous  The previous exception
     */
    public function __construct(
        ?string $key = null,
        array $replace = [],
        HttpStatusCode|int $code = 0,
        ?Exception $previous = null
    ) {
        $this->key = $key ?? static::$defaultKey;
        $message = __($this->key, $replace);

        // Handle HttpStatusCode enum or integer code
        if ($code instanceof HttpStatusCode) {
            $this->httpStatusCode = $code;
            $intCode = $code->value;
        } else {
            $this->httpStatusCode = HttpStatusCode::INTERNAL_SERVER_ERROR;
            $intCode = $code;
        }

        parent::__construct($message, $intCode, $previous);
    }

    /**
     * Create exception with custom reason.
     *
     * @param  string|null  $reason  The failure reason
     * @param  string|null  $key  Custom language key (optional)
     * @param  int  $code  The exception code
     */
    public static function withReason(?string $reason = null, ?string $key = null, int $code = 0): static
    {
        if ($reason && ! $key) {
            $key = static::$defaultKey.'_with_reason';
        }

        return new static($key, ['reason' => $reason], $code);
    }

    /**
     * Create exception with custom message key and parameters.
     *
     * @param  string  $key  The language key
     * @param  array  $replace  Parameters to replace in the message
     * @param  int  $code  The exception code
     */
    public static function withKey(string $key, array $replace = [], int $code = 0): static
    {
        return new static($key, $replace, $code);
    }

    /**
     * Create exception with direct message (bypasses localization).
     *
     * @param  string  $message  The error message
     * @param  int  $code  The exception code
     * @param  Exception|null  $previous  The previous exception
     */
    public static function withMessage(string $message, int $code = 0, ?Exception $previous = null): static
    {
        $instance = new static(null, [], $code, $previous);
        $instance->message = $message;

        return $instance;
    }

    /**
     * Get the default language key for this exception type.
     */
    public static function getDefaultKey(): string
    {
        return static::$defaultKey;
    }

    /**
     * Set the default language key for this exception type.
     *
     * @param  string  $key  The default language key
     */
    public static function setDefaultKey(string $key): void
    {
        static::$defaultKey = $key;
    }

    /**
     * Check if the exception has a specific error code.
     *
     * @param  int  $code  The error code to check
     */
    public function hasCode(int $code): bool
    {
        return $this->getCode() === $code;
    }

    /**
     * Check if the exception is of a specific type by class name.
     *
     * @param  string  $className  The class name to check
     */
    public function isType(string $className): bool
    {
        return $this instanceof $className;
    }

    /**
     * Get the HTTP status code for this exception.
     */
    public function getHttpStatusCode(): HttpStatusCode
    {
        return $this->httpStatusCode;
    }

    /**
     * Get the language key used for this exception.
     */
    public function getKey(): ?string
    {
        return $this->key;
    }

    /**
     * Get exception context information for logging.
     */
    public function getContext(): array
    {
        return [
            'exception_class' => static::class,
            'message' => $this->getMessage(),
            'code' => $this->getCode(),
            'http_status_code' => $this->httpStatusCode->value,
            'language_key' => $this->key,
            'file' => $this->getFile(),
            'line' => $this->getLine(),
        ];
    }
}
