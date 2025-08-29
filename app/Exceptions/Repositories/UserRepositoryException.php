<?php

namespace App\Exceptions\Repositories;

use App\Enums\HttpStatusCode;
use Exception;

/**
 * Exception for user repository-related errors.
 *
 * This exception class provides specialized error messages for user operations
 * in the LMS system, including authentication, authorization, and user status checks.
 *
 * @throws UserRepositoryException When user-specific repository operations fail
 */
class UserRepositoryException extends RepositoryException
{
    /**
     * The default language key for user repository exceptions.
     */
    protected static string $defaultKey = 'exceptions.repositories.user.user_not_found';

    /**
     * Create exception for user not found by email.
     *
     * @param  string  $email  The user email
     */
    public static function userNotFoundByEmail(string $email): static
    {
        return new static(
            'exceptions.repositories.user.user_not_found_by_email',
            ['email' => $email],
            HttpStatusCode::NOT_FOUND
        );
    }

    /**
     * Create exception for inactive user.
     *
     * @param  string|null  $userId  The user ID
     */
    public static function userNotActive(?string $userId = null): static
    {
        $key = $userId
            ? 'exceptions.repositories.user.user_not_active_with_id'
            : 'exceptions.repositories.user.user_not_active';

        return new static($key, ['id' => $userId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for banned user.
     *
     * @param  string|null  $userId  The user ID
     */
    public static function userBanned(?string $userId = null): static
    {
        $key = $userId
            ? 'exceptions.repositories.user.user_banned_with_id'
            : 'exceptions.repositories.user.user_banned';

        return new static($key, ['id' => $userId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for suspended user.
     *
     * @param  string|null  $userId  The user ID
     */
    public static function userSuspended(?string $userId = null): static
    {
        $key = $userId
            ? 'exceptions.repositories.user.user_suspended_with_id'
            : 'exceptions.repositories.user.user_suspended';

        return new static($key, ['id' => $userId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for invalid credentials.
     */
    public static function invalidCredentials(): static
    {
        return new static(
            'exceptions.repositories.user.invalid_credentials',
            [],
            HttpStatusCode::UNAUTHORIZED
        );
    }

    /**
     * Create exception for insufficient permissions.
     *
     * @param  string|null  $action  The action being attempted
     */
    public static function insufficientPermissions(?string $action = null): static
    {
        $key = $action
            ? 'exceptions.repositories.user.insufficient_permissions_with_action'
            : 'exceptions.repositories.user.insufficient_permissions';

        return new static($key, ['action' => $action], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for user not enrolled in course.
     *
     * @param  string  $userId  The user ID
     * @param  string  $courseId  The course ID
     */
    public static function notEnrolledInCourse(string $userId, string $courseId): static
    {
        return new static(
            'exceptions.repositories.user.not_enrolled_in_course',
            ['user_id' => $userId, 'course_id' => $courseId],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for user not teaching course.
     *
     * @param  string  $userId  The user ID
     * @param  string  $courseId  The course ID
     */
    public static function notTeachingCourse(string $userId, string $courseId): static
    {
        return new static(
            'exceptions.repositories.user.not_teaching_course',
            ['user_id' => $userId, 'course_id' => $courseId],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for user not managing course.
     *
     * @param  string  $userId  The user ID
     * @param  string  $courseId  The course ID
     */
    public static function notManagingCourse(string $userId, string $courseId): static
    {
        return new static(
            'exceptions.repositories.user.not_managing_course',
            ['user_id' => $userId, 'course_id' => $courseId],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for avatar upload failure.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function avatarUploadFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.user.avatar_upload_failed_with_reason'
            : 'exceptions.repositories.user.avatar_upload_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for avatar not found.
     *
     * @param  string  $userId  The user ID
     */
    public static function avatarNotFound(string $userId): static
    {
        return new static(
            'exceptions.repositories.user.avatar_not_found',
            ['user_id' => $userId],
            HttpStatusCode::NOT_FOUND
        );
    }

    /**
     * Create exception for email already exists.
     *
     * @param  string  $email  The email address
     */
    public static function emailAlreadyExists(string $email): static
    {
        return new static(
            'exceptions.repositories.user.email_already_exists',
            ['email' => $email],
            HttpStatusCode::CONFLICT
        );
    }

    /**
     * Create exception for role assignment failure.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function roleAssignmentFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.user.role_assignment_failed_with_reason'
            : 'exceptions.repositories.user.role_assignment_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
    }
}
