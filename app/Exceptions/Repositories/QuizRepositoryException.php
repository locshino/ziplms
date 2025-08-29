<?php

namespace App\Exceptions\Repositories;

use App\Enums\HttpStatusCode;
use Exception;

/**
 * Exception for quiz repository-related errors.
 *
 * This exception class provides specialized error messages for quiz operations
 * in the LMS system, including quiz access, attempts, and status management.
 *
 * @throws QuizRepositoryException When quiz-specific repository operations fail
 */
class QuizRepositoryException extends RepositoryException
{
    /**
     * The default language key for quiz repository exceptions.
     */
    protected static string $defaultKey = 'exceptions.repositories.quiz.quiz_not_found';

    /**
     * Create exception for quiz not published.
     *
     * @param  string|null  $quizId  The quiz ID
     */
    public static function notPublished(?string $quizId = null): static
    {
        $key = $quizId
            ? 'exceptions.repositories.quiz.quiz_not_published_with_id'
            : 'exceptions.repositories.quiz.quiz_not_published';

        return new static($key, ['id' => $quizId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for quiz not available.
     *
     * @param  string|null  $quizId  The quiz ID
     */
    public static function notAvailable(?string $quizId = null): static
    {
        $key = $quizId
            ? 'exceptions.repositories.quiz.quiz_not_available_with_id'
            : 'exceptions.repositories.quiz.quiz_not_available';

        return new static($key, ['id' => $quizId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for quiz not started.
     *
     * @param  string|null  $quizId  The quiz ID
     */
    public static function notStarted(?string $quizId = null): static
    {
        $key = $quizId
            ? 'exceptions.repositories.quiz.quiz_not_started_with_id'
            : 'exceptions.repositories.quiz.quiz_not_started';

        return new static($key, ['id' => $quizId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for quiz ended.
     *
     * @param  string|null  $quizId  The quiz ID
     */
    public static function ended(?string $quizId = null): static
    {
        $key = $quizId
            ? 'exceptions.repositories.quiz.quiz_ended_with_id'
            : 'exceptions.repositories.quiz.quiz_ended';

        return new static($key, ['id' => $quizId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for attempt not found.
     *
     * @param  string|null  $attemptId  The attempt ID
     */
    public static function attemptNotFound(?string $attemptId = null): static
    {
        $key = $attemptId
            ? 'exceptions.repositories.quiz.attempt_not_found_with_id'
            : 'exceptions.repositories.quiz.attempt_not_found';

        return new static($key, ['id' => $attemptId], HttpStatusCode::NOT_FOUND);
    }

    /**
     * Create exception for maximum attempts reached.
     *
     * @param  string  $quizId  The quiz ID
     * @param  int  $maxAttempts  The maximum attempts allowed
     */
    public static function maxAttemptsReached(string $quizId, int $maxAttempts): static
    {
        return new static(
            'exceptions.repositories.quiz.max_attempts_reached',
            ['quiz_id' => $quizId, 'max_attempts' => $maxAttempts],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for attempt already submitted.
     *
     * @param  string  $attemptId  The attempt ID
     */
    public static function attemptAlreadySubmitted(string $attemptId): static
    {
        return new static(
            'exceptions.repositories.quiz.attempt_already_submitted',
            ['attempt_id' => $attemptId],
            HttpStatusCode::CONFLICT
        );
    }

    /**
     * Create exception for attempt not submitted.
     *
     * @param  string  $attemptId  The attempt ID
     */
    public static function attemptNotSubmitted(string $attemptId): static
    {
        return new static(
            'exceptions.repositories.quiz.attempt_not_submitted',
            ['attempt_id' => $attemptId],
            HttpStatusCode::BAD_REQUEST
        );
    }

    /**
     * Create exception for time limit exceeded.
     *
     * @param  string  $attemptId  The attempt ID
     */
    public static function timeLimitExceeded(string $attemptId): static
    {
        return new static(
            'exceptions.repositories.quiz.time_limit_exceeded',
            ['attempt_id' => $attemptId],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for attempt start failure.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function attemptStartFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.quiz.attempt_start_failed_with_reason'
            : 'exceptions.repositories.quiz.attempt_start_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for attempt submission failure.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function attemptSubmissionFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.quiz.attempt_submission_failed_with_reason'
            : 'exceptions.repositories.quiz.attempt_submission_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for statistics calculation failure.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function statisticsCalculationFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.quiz.statistics_calculation_failed_with_reason'
            : 'exceptions.repositories.quiz.statistics_calculation_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * Create exception for insufficient permissions.
     *
     * @param  string|null  $action  The action being attempted
     */
    public static function insufficientPermissions(?string $action = null): static
    {
        $key = $action
            ? 'exceptions.repositories.quiz.insufficient_permissions_with_action'
            : 'exceptions.repositories.quiz.insufficient_permissions';

        return new static($key, ['action' => $action], HttpStatusCode::FORBIDDEN);
    }
}
