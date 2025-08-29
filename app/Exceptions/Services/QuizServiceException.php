<?php

namespace App\Exceptions\Services;

use Exception;

class QuizServiceException extends Exception
{
    public static function quizNotFound($quizId = null): self
    {
        return new self('Quiz not found'.($quizId ? ": $quizId" : ''));
    }

    public static function quizNotPublished(): self
    {
        return new self('Quiz is not published or active.');
    }

    public static function quizNotStarted(): self
    {
        return new self('Quiz has not started yet.');
    }

    public static function quizExpired(): self
    {
        return new self('Quiz has expired.');
    }

    public static function maxAttemptsExceeded(): self
    {
        return new self('Maximum number of attempts exceeded.');
    }

    public static function concurrentAttemptNotAllowed(): self
    {
        return new self('Concurrent quiz attempt is not allowed.');
    }

    public static function invalidAnswerSubmission(): self
    {
        return new self('Invalid answer submission.');
    }

    public static function quizAttemptNotFound(): self
    {
        return new self('Quiz attempt not found.');
    }

    public static function quizAttemptAlreadyCompleted(): self
    {
        return new self('Quiz attempt already completed.');
    }

    public static function quizNotActive(): self
    {
        return new self('Quiz is not active for this student.');
    }

    public static function calculationError($msg = null): self
    {
        return new self('Quiz score calculation error'.($msg ? ": $msg" : ''));
    }

    public static function databaseError($msg = null): self
    {
        return new self('Database error'.($msg ? ": $msg" : ''));
    }
}
