<?php

namespace App\Exceptions\Repositories;

/**
 * Assignment repository exception class.
 *
 * Handles assignment-specific repository exceptions with localized messages.
 */
class AssignmentRepositoryException extends RepositoryException
{
    /**
     * Assignment not found exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function notFound(?string $id = null, ?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.not_found_with_reason'
            : 'exceptions_repositories_assignmentrepository.not_found';

        if ($reason) {
            $replace['reason'] = $reason;
        }

        if ($id) {
            $replace['id'] = $id;
        }

        return new static($key, $replace, 404);
    }

    /**
     * Assignment not found by ID exception.
     *
     * @param  string  $id  The assignment ID
     * @param  string|null  $reason  The failure reason
     */
    public static function notFoundById(string $id, ?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.not_found_by_id_with_reason'
            : 'exceptions_repositories_assignmentrepository.not_found_by_id';

        return new static($key, ['id' => $id, 'reason' => $reason], 404);
    }

    /**
     * No assignments found for course exception.
     *
     * @param  string  $course  The course identifier
     * @param  string|null  $reason  The failure reason
     */
    public static function notFoundByCourse(string $course, ?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.not_found_by_course_with_reason'
            : 'exceptions_repositories_assignmentrepository.not_found_by_course';

        return new static($key, ['course' => $course, 'reason' => $reason], 404);
    }

    /**
     * Invalid course ID exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidCourse(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.invalid_course_with_reason'
            : 'exceptions_repositories_assignmentrepository.invalid_course';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Invalid student ID exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidStudent(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.invalid_student_with_reason'
            : 'exceptions_repositories_assignmentrepository.invalid_student';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Invalid assignment data exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidAssignmentData(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.invalid_assignment_data_with_reason'
            : 'exceptions_repositories_assignmentrepository.invalid_assignment_data';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Assignment title required exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function titleRequired(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.title_required_with_reason'
            : 'exceptions_repositories_assignmentrepository.title_required';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Assignment title too long exception.
     *
     * @param  int  $max  The maximum length
     * @param  string|null  $reason  The failure reason
     */
    public static function titleTooLong(int $max, ?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.title_too_long_with_reason'
            : 'exceptions_repositories_assignmentrepository.title_too_long';

        return new static($key, ['max' => $max, 'reason' => $reason], 400);
    }

    /**
     * Assignment description required exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function descriptionRequired(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.description_required_with_reason'
            : 'exceptions_repositories_assignmentrepository.description_required';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Invalid due date exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidDueDate(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.invalid_due_date_with_reason'
            : 'exceptions_repositories_assignmentrepository.invalid_due_date';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Invalid end date exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidEndDate(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.invalid_end_date_with_reason'
            : 'exceptions_repositories_assignmentrepository.invalid_end_date';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Due date in past exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function dueDatePast(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.due_date_past_with_reason'
            : 'exceptions_repositories_assignmentrepository.due_date_past';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * End date before due date exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function endDateBeforeDue(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.end_date_before_due_with_reason'
            : 'exceptions_repositories_assignmentrepository.end_date_before_due';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Invalid maximum score exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidMaxScore(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.invalid_max_score_with_reason'
            : 'exceptions_repositories_assignmentrepository.invalid_max_score';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Negative maximum score exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function maxScoreNegative(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.max_score_negative_with_reason'
            : 'exceptions_repositories_assignmentrepository.max_score_negative';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Assignment already published exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function assignmentPublished(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.assignment_published_with_reason'
            : 'exceptions_repositories_assignmentrepository.assignment_published';

        return new static($key, ['reason' => $reason], 409);
    }

    /**
     * Assignment not published exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function assignmentNotPublished(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.assignment_not_published_with_reason'
            : 'exceptions_repositories_assignmentrepository.assignment_not_published';

        return new static($key, ['reason' => $reason], 409);
    }

    /**
     * Assignment has submissions exception.
     */
    public static function assignmentHasSubmissions(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.assignment_has_submissions_with_reason'
            : 'exceptions_repositories_assignmentrepository.assignment_has_submissions';

        return new static($key, ['reason' => $reason], 409);
    }

    /**
     * Assignment overdue exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function assignmentOverdue(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.assignment_overdue_with_reason'
            : 'exceptions_repositories_assignmentrepository.assignment_overdue';

        return new static($key, ['reason' => $reason], 409);
    }

    /**
     * Assignment not available exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function assignmentNotAvailable(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.assignment_not_available_with_reason'
            : 'exceptions_repositories_assignmentrepository.assignment_not_available';

        return new static($key, ['reason' => $reason], 409);
    }

    /**
     * Submission period ended exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function submissionPeriodEnded(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.submission_period_ended_with_reason'
            : 'exceptions_repositories_assignmentrepository.submission_period_ended';

        return new static($key, ['reason' => $reason], 409);
    }

    /**
     * Invalid assignment status exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function invalidStatus(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.invalid_status_with_reason'
            : 'exceptions_repositories_assignmentrepository.invalid_status';

        return new static($key, ['reason' => $reason], 400);
    }

    /**
     * Duplicate assignment title exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function duplicateTitle(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.duplicate_title_with_reason'
            : 'exceptions_repositories_assignmentrepository.duplicate_title';

        return new static($key, ['reason' => $reason], 409);
    }

    /**
     * Course not found for assignment exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function courseNotFound(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.course_not_found_with_reason'
            : 'exceptions_repositories_assignmentrepository.course_not_found';

        return new static($key, ['reason' => $reason], 404);
    }

    /**
     * Instructor mismatch exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function instructorMismatch(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.instructor_mismatch_with_reason'
            : 'exceptions_repositories_assignmentrepository.instructor_mismatch';

        return new static($key, ['reason' => $reason], 409);
    }

    /**
     * Student not enrolled exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function studentNotEnrolled(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.student_not_enrolled_with_reason'
            : 'exceptions_repositories_assignmentrepository.student_not_enrolled';

        return new static($key, ['reason' => $reason], 403);
    }

    /**
     * Grading error exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function gradingError(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.grading_error_with_reason'
            : 'exceptions_repositories_assignmentrepository.grading_error';

        return new static($key, ['reason' => $reason], 500);
    }

    /**
     * Submission not found exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function submissionNotFound(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.submission_not_found_with_reason'
            : 'exceptions_repositories_assignmentrepository.submission_not_found';

        return new static($key, ['reason' => $reason], 404);
    }

    /**
     * Create assignment failed exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function createFailed(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.create_failed_with_reason'
            : 'exceptions_repositories_assignmentrepository.create_failed';

        return new static($key, ['reason' => $reason], 500);
    }

    /**
     * Update assignment failed exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function updateFailed(?string $id = null, ?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.update_failed_with_reason'
            : 'exceptions_repositories_assignmentrepository.update_failed';

        if ($reason) {
            $replace['reason'] = $reason;
        }

        if ($id) {
            $replace['id'] = $id;
        }

        return new static($key, $replace, 500);
    }

    /**
     * Delete assignment failed exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function deleteFailed(?string $id = null, ?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.delete_failed_with_reason'
            : 'exceptions_repositories_assignmentrepository.delete_failed';

        if ($reason) {
            $replace['reason'] = $reason;
        }

        if ($id) {
            $replace['id'] = $id;
        }

        return new static($key, $replace, 500);
    }

    /**
     * Publish assignment failed exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function publishFailed(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.publish_failed_with_reason'
            : 'exceptions_repositories_assignmentrepository.publish_failed';

        return new static($key, ['reason' => $reason], 500);
    }

    /**
     * Unpublish assignment failed exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function unpublishFailed(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.unpublish_failed_with_reason'
            : 'exceptions_repositories_assignmentrepository.unpublish_failed';

        return new static($key, ['reason' => $reason], 500);
    }

    /**
     * Database error exception.
     *
     * @param  string|null  $reason  The failure reason
     */
    public static function databaseError(?string $reason = null): self
    {
        $key = $reason
            ? 'exceptions_repositories_assignmentrepository.database_error_with_reason'
            : 'exceptions_repositories_assignmentrepository.database_error';

        return new static($key, ['reason' => $reason], 500);
    }
}
