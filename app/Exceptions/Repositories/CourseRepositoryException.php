<?php

namespace App\Exceptions\Repositories;

use App\Enums\HttpStatusCode;
use Exception;

/**
 * Exception for course repository-related errors.
 *
 * This exception class provides specialized error messages for course operations
 * in the LMS system, including course access, enrollment, and management.
 *
 * @throws CourseRepositoryException When course-specific repository operations fail
 */
class CourseRepositoryException extends RepositoryException
{
    /**
     * The default language key for course repository exceptions.
     *
     * @var string
     */
    protected static string $defaultKey = 'exceptions.repositories.course.course_not_found';

    /**
     * Create exception for course not published.
     *
     * @param  string|null  $courseId  The course ID
     * @return static
     */
    public static function notPublished(?string $courseId = null): static
    {
        $key = $courseId
            ? 'exceptions.repositories.course.course_not_published_with_id'
            : 'exceptions.repositories.course.course_not_published';

        return new static($key, ['id' => $courseId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for course not available.
     *
     * @param  string|null  $courseId  The course ID
     * @return static
     */
    public static function notAvailable(?string $courseId = null): static
    {
        $key = $courseId
            ? 'exceptions.repositories.course.course_not_available_with_id'
            : 'exceptions.repositories.course.course_not_available';

        return new static($key, ['id' => $courseId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for course enrollment closed.
     *
     * @param  string|null  $courseId  The course ID
     * @return static
     */
    public static function enrollmentClosed(?string $courseId = null): static
    {
        $key = $courseId
            ? 'exceptions.repositories.course.enrollment_closed_with_id'
            : 'exceptions.repositories.course.enrollment_closed';

        return new static($key, ['id' => $courseId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for course capacity full.
     *
     * @param  string|null  $courseId  The course ID
     * @param  int|null  $capacity  The course capacity
     * @return static
     */
    public static function capacityFull(?string $courseId = null, ?int $capacity = null): static
    {
        return new static(
            'exceptions.repositories.course.capacity_full',
            ['id' => $courseId, 'capacity' => $capacity],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for student already enrolled.
     *
     * @param  string  $courseId  The course ID
     * @param  string  $studentId  The student ID
     * @return static
     */
    public static function alreadyEnrolled(string $courseId, string $studentId): static
    {
        return new static(
            'exceptions.repositories.course.already_enrolled',
            ['course_id' => $courseId, 'student_id' => $studentId],
            HttpStatusCode::CONFLICT
        );
    }

    /**
     * Create exception for student not enrolled.
     *
     * @param  string  $courseId  The course ID
     * @param  string  $studentId  The student ID
     * @return static
     */
    public static function notEnrolled(string $courseId, string $studentId): static
    {
        return new static(
            'exceptions.repositories.course.not_enrolled',
            ['course_id' => $courseId, 'student_id' => $studentId],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for enrollment failure.
     *
     * @param  string  $courseId  The course ID
     * @param  string  $studentId  The student ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function enrollmentFailed(string $courseId, string $studentId, ?string $reason = null): static
    {
        return new static(
            'exceptions.repositories.course.enrollment_failed',
            ['course_id' => $courseId, 'student_id' => $studentId, 'reason' => $reason],
            HttpStatusCode::BAD_REQUEST
        );
    }

    /**
     * Create exception for unenrollment failure.
     *
     * @param  string  $courseId  The course ID
     * @param  string  $studentId  The student ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function unenrollmentFailed(string $courseId, string $studentId, ?string $reason = null): static
    {
        return new static(
            'exceptions.repositories.course.unenrollment_failed',
            ['course_id' => $courseId, 'student_id' => $studentId, 'reason' => $reason],
            HttpStatusCode::BAD_REQUEST
        );
    }

    /**
     * Create exception for instructor not assigned.
     *
     * @param  string  $courseId  The course ID
     * @param  string  $instructorId  The instructor ID
     * @return static
     */
    public static function instructorNotAssigned(string $courseId, string $instructorId): static
    {
        return new static(
            'exceptions.repositories.course.instructor_not_assigned',
            ['course_id' => $courseId, 'instructor_id' => $instructorId],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for instructor already assigned.
     *
     * @param  string  $courseId  The course ID
     * @param  string  $instructorId  The instructor ID
     * @return static
     */
    public static function instructorAlreadyAssigned(string $courseId, string $instructorId): static
    {
        return new static(
            'exceptions.repositories.course.instructor_already_assigned',
            ['course_id' => $courseId, 'instructor_id' => $instructorId],
            HttpStatusCode::CONFLICT
        );
    }

    /**
     * Create exception for course progress calculation failure.
     *
     * @param  string  $courseId  The course ID
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function progressCalculationFailed(string $courseId, ?string $reason = null): static
    {
        return new static(
            'exceptions.repositories.course.progress_calculation_failed',
            ['course_id' => $courseId, 'reason' => $reason],
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
            ? 'exceptions.repositories.course.statistics_calculation_failed_with_reason'
            : 'exceptions.repositories.course.statistics_calculation_failed';

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
            ? 'exceptions.repositories.course.insufficient_permissions_with_action'
            : 'exceptions.repositories.course.insufficient_permissions';

        return new static($key, ['action' => $action], HttpStatusCode::FORBIDDEN);
    }
}