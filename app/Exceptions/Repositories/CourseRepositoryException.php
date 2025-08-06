<?php

namespace App\Exceptions\Repositories;

/**
 * Exception for course repository-related errors.
 *
 * This exception class provides localized error messages specific to course operations.
 *
 * @throws CourseRepositoryException When course repository operations fail
 */
class CourseRepositoryException extends RepositoryException
{
    /**
     * Create exception for course not found.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseNotFound(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_not_found_with_reason'
            : 'exceptions_repositories_courserepository.course_not_found';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course title required.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseTitleRequired(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_title_required_with_reason'
            : 'exceptions_repositories_courserepository.course_title_required';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course title too long.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseTitleTooLong(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_title_too_long_with_reason'
            : 'exceptions_repositories_courserepository.course_title_too_long';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course description required.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseDescriptionRequired(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_description_required_with_reason'
            : 'exceptions_repositories_courserepository.course_description_required';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for invalid instructor.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidInstructor(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.invalid_instructor_with_reason'
            : 'exceptions_repositories_courserepository.invalid_instructor';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course already published.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseAlreadyPublished(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_already_published_with_reason'
            : 'exceptions_repositories_courserepository.course_already_published';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course not published.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseNotPublished(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_not_published_with_reason'
            : 'exceptions_repositories_courserepository.course_not_published';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for cannot delete published course.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function cannotDeletePublishedCourse(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.cannot_delete_published_course_with_reason'
            : 'exceptions_repositories_courserepository.cannot_delete_published_course';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course has enrollments.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseHasEnrollments(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_has_enrollments_with_reason'
            : 'exceptions_repositories_courserepository.course_has_enrollments';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course has assignments.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseHasAssignments(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_has_assignments_with_reason'
            : 'exceptions_repositories_courserepository.course_has_assignments';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course has quizzes.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseHasQuizzes(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_has_quizzes_with_reason'
            : 'exceptions_repositories_courserepository.course_has_quizzes';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for invalid course status.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidCourseStatus(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.invalid_course_status_with_reason'
            : 'exceptions_repositories_courserepository.invalid_course_status';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for invalid course category.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidCourseCategory(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.invalid_course_category_with_reason'
            : 'exceptions_repositories_courserepository.invalid_course_category';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course capacity exceeded.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseCapacityExceeded(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_capacity_exceeded_with_reason'
            : 'exceptions_repositories_courserepository.course_capacity_exceeded';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course enrollment closed.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseEnrollmentClosed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_enrollment_closed_with_reason'
            : 'exceptions_repositories_courserepository.course_enrollment_closed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course not available.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseNotAvailable(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_not_available_with_reason'
            : 'exceptions_repositories_courserepository.course_not_available';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for duplicate course title.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function duplicateCourseTitle(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.duplicate_course_title_with_reason'
            : 'exceptions_repositories_courserepository.duplicate_course_title';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for invalid course duration.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidCourseDuration(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.invalid_course_duration_with_reason'
            : 'exceptions_repositories_courserepository.invalid_course_duration';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for invalid course price.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidCoursePrice(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.invalid_course_price_with_reason'
            : 'exceptions_repositories_courserepository.invalid_course_price';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for course dates conflict.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseDatesConflict(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.course_dates_conflict_with_reason'
            : 'exceptions_repositories_courserepository.course_dates_conflict';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for create course failed.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function createCourseFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.create_course_failed_with_reason'
            : 'exceptions_repositories_courserepository.create_course_failed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for update course failed.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function updateCourseFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.update_course_failed_with_reason'
            : 'exceptions_repositories_courserepository.update_course_failed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for delete course failed.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function deleteCourseFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.delete_course_failed_with_reason'
            : 'exceptions_repositories_courserepository.delete_course_failed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for publish course failed.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function publishCourseFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.publish_course_failed_with_reason'
            : 'exceptions_repositories_courserepository.publish_course_failed';

        return new static($key, ['reason' => $reason]);
    }

    /**
     * Create exception for unpublish course failed.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function unpublishCourseFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_courserepository.unpublish_course_failed_with_reason'
            : 'exceptions_repositories_courserepository.unpublish_course_failed';

        return new static($key, ['reason' => $reason]);
    }
}
