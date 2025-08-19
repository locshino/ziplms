<?php

namespace App\Exceptions\Repositories;

use App\Enums\HttpStatusCode;
use Exception;

/**
 * Exception for badge repository-related errors.
 *
 * This exception class provides specialized error messages for badge operations
 * in the LMS system, including badge awarding, conditions, and management.
 *
 * @throws BadgeRepositoryException When badge-specific repository operations fail
 */
class BadgeRepositoryException extends RepositoryException
{
    /**
     * The default language key for badge repository exceptions.
     *
     * @var string
     */
    protected static string $defaultKey = 'exceptions.repositories.badge.badge_not_found';

    /**
     * Create exception for badge not active.
     *
     * @param  string|null  $badgeId  The badge ID
     * @return static
     */
    public static function notActive(?string $badgeId = null): static
    {
        $key = $badgeId
            ? 'exceptions.repositories.badge.badge_not_active_with_id'
            : 'exceptions.repositories.badge.badge_not_active';

        return new static($key, ['id' => $badgeId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for badge not available.
     *
     * @param  string|null  $badgeId  The badge ID
     * @return static
     */
    public static function notAvailable(?string $badgeId = null): static
    {
        $key = $badgeId
            ? 'exceptions.repositories.badge.badge_not_available_with_id'
            : 'exceptions.repositories.badge.badge_not_available';

        return new static($key, ['id' => $badgeId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for badge already awarded.
     *
     * @param  string  $badgeId  The badge ID
     * @param  string  $userId  The user ID
     * @return static
     */
    public static function alreadyAwarded(string $badgeId, string $userId): static
    {
        return new static(
            'exceptions.repositories.badge.already_awarded',
            ['badge_id' => $badgeId, 'user_id' => $userId],
            HttpStatusCode::CONFLICT
        );
    }

    /**
     * Create exception for badge not awarded.
     *
     * @param  string  $badgeId  The badge ID
     * @param  string  $userId  The user ID
     * @return static
     */
    public static function notAwarded(string $badgeId, string $userId): static
    {
        return new static(
            'exceptions.repositories.badge.not_awarded',
            ['badge_id' => $badgeId, 'user_id' => $userId],
            HttpStatusCode::NOT_FOUND
        );
    }

    /**
     * Create exception for conditions not met.
     *
     * @param  string  $badgeId  The badge ID
     * @param  string  $userId  The user ID
     * @param  array|null  $missingConditions  The missing conditions
     * @return static
     */
    public static function conditionsNotMet(string $badgeId, string $userId, ?array $missingConditions = null): static
    {
        return new static(
            'exceptions.repositories.badge.conditions_not_met',
            ['badge_id' => $badgeId, 'user_id' => $userId, 'missing_conditions' => $missingConditions],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for condition not found.
     *
     * @param  string|null  $conditionId  The condition ID
     * @return static
     */
    public static function conditionNotFound(?string $conditionId = null): static
    {
        $key = $conditionId
            ? 'exceptions.repositories.badge.condition_not_found_with_id'
            : 'exceptions.repositories.badge.condition_not_found';

        return new static($key, ['condition_id' => $conditionId], HttpStatusCode::NOT_FOUND);
    }

    /**
     * Create exception for invalid condition type.
     *
     * @param  string  $conditionType  The invalid condition type
     * @return static
     */
    public static function invalidConditionType(string $conditionType): static
    {
        return new static(
            'exceptions.repositories.badge.invalid_condition_type',
            ['condition_type' => $conditionType],
            HttpStatusCode::BAD_REQUEST
        );
    }

    /**
     * Create exception for badge awarding failure.
     *
     * @param  string  $badgeId  The badge ID
     * @param  string  $userId  The user ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function awardingFailed(string $badgeId, string $userId, ?string $reason = null): static
    {
        return new static(
            'exceptions.repositories.badge.awarding_failed',
            ['badge_id' => $badgeId, 'user_id' => $userId, 'reason' => $reason],
            HttpStatusCode::INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Create exception for badge revocation failure.
     *
     * @param  string  $badgeId  The badge ID
     * @param  string  $userId  The user ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function revocationFailed(string $badgeId, string $userId, ?string $reason = null): static
    {
        return new static(
            'exceptions.repositories.badge.revocation_failed',
            ['badge_id' => $badgeId, 'user_id' => $userId, 'reason' => $reason],
            HttpStatusCode::INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Create exception for condition evaluation failure.
     *
     * @param  string  $conditionId  The condition ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function conditionEvaluationFailed(string $conditionId, ?string $reason = null): static
    {
        return new static(
            'exceptions.repositories.badge.condition_evaluation_failed',
            ['condition_id' => $conditionId, 'reason' => $reason],
            HttpStatusCode::INTERNAL_SERVER_ERROR
        );
    }

    /**
     * Create exception for statistics calculation failure.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function statisticsCalculationFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.badge.statistics_calculation_failed_with_reason'
            : 'exceptions.repositories.badge.statistics_calculation_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::INTERNAL_SERVER_ERROR);
    }

    /**
     * Create exception for insufficient permissions.
     *
     * @param  string|null  $action  The action being attempted
     * @return static
     */
    public static function insufficientPermissions(?string $action = null): static
    {
        $key = $action
            ? 'exceptions.repositories.badge.insufficient_permissions_with_action'
            : 'exceptions.repositories.badge.insufficient_permissions';

        return new static($key, ['action' => $action], HttpStatusCode::FORBIDDEN);
    }
}