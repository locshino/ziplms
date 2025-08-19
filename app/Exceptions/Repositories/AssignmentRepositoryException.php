<?php

namespace App\Exceptions\Repositories;

use App\Enums\HttpStatusCode;
use Exception;

/**
 * Exception for assignment repository-related errors.
 *
 * This exception class provides specialized error messages for assignment operations
 * in the LMS system, including assignment access, submissions, and status management.
 *
 * @throws AssignmentRepositoryException When assignment-specific repository operations fail
 */
class AssignmentRepositoryException extends RepositoryException
{
    /**
     * The default language key for assignment repository exceptions.
     *
     * @var string
     */
    protected static string $defaultKey = 'exceptions.repositories.assignment.assignment_not_found';

    /**
     * Create exception for assignment not published.
     *
     * @param  string|null  $assignmentId  The assignment ID
     * @return static
     */
    public static function notPublished(?string $assignmentId = null): static
    {
        $key = $assignmentId
            ? 'exceptions.repositories.assignment.assignment_not_published_with_id'
            : 'exceptions.repositories.assignment.assignment_not_published';

        return new static($key, ['id' => $assignmentId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for assignment not available.
     *
     * @param  string|null  $assignmentId  The assignment ID
     * @return static
     */
    public static function notAvailable(?string $assignmentId = null): static
    {
        $key = $assignmentId
            ? 'exceptions.repositories.assignment.assignment_not_available_with_id'
            : 'exceptions.repositories.assignment.assignment_not_available';

        return new static($key, ['id' => $assignmentId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for assignment not started.
     *
     * @param  string|null  $assignmentId  The assignment ID
     * @return static
     */
    public static function notStarted(?string $assignmentId = null): static
    {
        $key = $assignmentId
            ? 'exceptions.repositories.assignment.assignment_not_started_with_id'
            : 'exceptions.repositories.assignment.assignment_not_started';

        return new static($key, ['id' => $assignmentId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for assignment ended.
     *
     * @param  string|null  $assignmentId  The assignment ID
     * @return static
     */
    public static function ended(?string $assignmentId = null): static
    {
        $key = $assignmentId
            ? 'exceptions.repositories.assignment.assignment_ended_with_id'
            : 'exceptions.repositories.assignment.assignment_ended';

        return new static($key, ['id' => $assignmentId], HttpStatusCode::FORBIDDEN);
    }

    /**
     * Create exception for submission not found.
     *
     * @param  string|null  $submissionId  The submission ID
     * @return static
     */
    public static function submissionNotFound(?string $submissionId = null): static
    {
        $key = $submissionId
            ? 'exceptions.repositories.assignment.submission_not_found_with_id'
            : 'exceptions.repositories.assignment.submission_not_found';

        return new static($key, ['id' => $submissionId], HttpStatusCode::NOT_FOUND);
    }

    /**
     * Create exception for submission already exists.
     *
     * @param  string  $assignmentId  The assignment ID
     * @param  string  $studentId  The student ID
     * @return static
     */
    public static function submissionAlreadyExists(string $assignmentId, string $studentId): static
    {
        return new static(
            'exceptions.repositories.assignment.submission_already_exists',
            ['assignment_id' => $assignmentId, 'student_id' => $studentId],
            HttpStatusCode::CONFLICT
        );
    }

    /**
     * Create exception for submission already graded.
     *
     * @param  string  $submissionId  The submission ID
     * @return static
     */
    public static function submissionAlreadyGraded(string $submissionId): static
    {
        return new static(
            'exceptions.repositories.assignment.submission_already_graded',
            ['submission_id' => $submissionId],
            HttpStatusCode::CONFLICT
        );
    }

    /**
     * Create exception for submission not graded.
     *
     * @param  string  $submissionId  The submission ID
     * @return static
     */
    public static function submissionNotGraded(string $submissionId): static
    {
        return new static(
            'exceptions.repositories.assignment.submission_not_graded',
            ['submission_id' => $submissionId],
            HttpStatusCode::BAD_REQUEST
        );
    }

    /**
     * Create exception for late submission.
     *
     * @param  string  $assignmentId  The assignment ID
     * @return static
     */
    public static function lateSubmission(string $assignmentId): static
    {
        return new static(
            'exceptions.repositories.assignment.late_submission',
            ['assignment_id' => $assignmentId],
            HttpStatusCode::FORBIDDEN
        );
    }

    /**
     * Create exception for file upload failure.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function fileUploadFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.assignment.file_upload_failed_with_reason'
            : 'exceptions.repositories.assignment.file_upload_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
    }

    /**
     * Create exception for grading failure.
     *
     * @param  string|null  $reason  The failure reason
     * @return static
     */
    public static function gradingFailed(?string $reason = null): static
    {
        $key = $reason
            ? 'exceptions.repositories.assignment.grading_failed_with_reason'
            : 'exceptions.repositories.assignment.grading_failed';

        return new static($key, ['reason' => $reason], HttpStatusCode::BAD_REQUEST);
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
            ? 'exceptions.repositories.assignment.statistics_calculation_failed_with_reason'
            : 'exceptions.repositories.assignment.statistics_calculation_failed';

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
            ? 'exceptions.repositories.assignment.insufficient_permissions_with_action'
            : 'exceptions.repositories.assignment.insufficient_permissions';

        return new static($key, ['action' => $action], HttpStatusCode::FORBIDDEN);
    }
}