<?php

namespace App\Exceptions\Repositories;

/**
 * Exception for user repository-related errors.
 * 
 * This exception class provides localized error messages specific to user operations.
 * 
 * @throws UserRepositoryException When user repository operations fail
 */
class UserRepositoryException extends RepositoryException
{
    /**
     * Create exception for user not found.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function userNotFound(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.user_not_found_with_reason'
            : 'exceptions_repositories_userrepository.user_not_found';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for user not found by email.
     *
     * @param string $email The email address
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function userNotFoundByEmail(string $email, ?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.user_not_found_with_email_and_reason'
            : 'exceptions_repositories_userrepository.user_not_found_with_email';
        
        return new static($key, ['email' => $email, 'reason' => $reason]);
    }

    /**
     * Create exception for email already exists.
     *
     * @param string|null $email The email address
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function emailAlreadyExists(?string $email = null, ?string $reason = null): static
    {
        if ($reason) {
            $key = $email 
                ? 'exceptions_repositories_userrepository.email_already_taken_with_reason'
                : 'exceptions_repositories_userrepository.email_already_exists_with_reason';
        } else {
            $key = $email 
                ? 'exceptions_repositories_userrepository.email_already_taken'
                : 'exceptions_repositories_userrepository.email_already_exists';
        }
        
        return new static($key, ['email' => $email, 'reason' => $reason]);
    }

    /**
     * Create exception for invalid email format.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function invalidEmailFormat(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.invalid_email_format_with_reason'
            : 'exceptions_repositories_userrepository.invalid_email_format';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for weak password.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function passwordTooWeak(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.password_too_weak_with_reason'
            : 'exceptions_repositories_userrepository.password_too_weak';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for invalid role.
     *
     * @param string|null $role The role name
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function invalidRole(?string $role = null, ?string $reason = null): static
    {
        if ($reason) {
            $key = $role 
                ? 'exceptions_repositories_userrepository.role_not_found_with_reason'
                : 'exceptions_repositories_userrepository.invalid_role_with_reason';
        } else {
            $key = $role 
                ? 'exceptions_repositories_userrepository.role_not_found'
                : 'exceptions_repositories_userrepository.invalid_role';
        }
        
        return new static($key, ['role' => $role, 'reason' => $reason]);
    }

    /**
     * Create exception for user already has role.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function userAlreadyHasRole(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.user_already_has_role_with_reason'
            : 'exceptions_repositories_userrepository.user_already_has_role';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for user does not have role.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function userDoesNotHaveRole(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.user_does_not_have_role_with_reason'
            : 'exceptions_repositories_userrepository.user_does_not_have_role';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for cannot delete admin.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function cannotDeleteAdmin(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.cannot_delete_admin_with_reason'
            : 'exceptions_repositories_userrepository.cannot_delete_admin';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for cannot modify own role.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function cannotModifyOwnRole(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.cannot_modify_own_role_with_reason'
            : 'exceptions_repositories_userrepository.cannot_modify_own_role';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for inactive user.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function userIsInactive(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.user_is_inactive_with_reason'
            : 'exceptions_repositories_userrepository.user_is_inactive';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for suspended user.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function userIsSuspended(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.user_is_suspended_with_reason'
            : 'exceptions_repositories_userrepository.user_is_suspended';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for invalid user status.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function invalidUserStatus(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.invalid_user_status_with_reason'
            : 'exceptions_repositories_userrepository.invalid_user_status';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for user has active enrollments.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function userHasActiveEnrollments(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.user_has_active_enrollments_with_reason'
            : 'exceptions_repositories_userrepository.user_has_active_enrollments';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for user has pending assignments.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function userHasPendingAssignments(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.user_has_pending_assignments_with_reason'
            : 'exceptions_repositories_userrepository.user_has_pending_assignments';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for instructor has active courses.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function instructorHasActiveCourses(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.instructor_has_active_courses_with_reason'
            : 'exceptions_repositories_userrepository.instructor_has_active_courses';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for create user failed.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function createUserFailed(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.create_user_failed_with_reason'
            : 'exceptions_repositories_userrepository.create_user_failed';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for update user failed.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function updateUserFailed(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.update_user_failed_with_reason'
            : 'exceptions_repositories_userrepository.update_user_failed';
        
        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for delete user failed.
     *
     * @param string|null $reason The failure reason
     * @return static
     */
    public static function deleteUserFailed(?string $reason = null): static
    {
        $key = $reason 
            ? 'exceptions_repositories_userrepository.delete_user_failed_with_reason'
            : 'exceptions_repositories_userrepository.delete_user_failed';
        
        return new static($key, ['reason' => $reason]);
    }
}