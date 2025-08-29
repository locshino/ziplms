<?php

namespace App\Exceptions\Services;

use Exception;

/**
 * Exception class for Assessment Service operations.
 */
class AssessmentServiceException extends Exception
{
    /**
     * Create exception for assignment not found.
     */
    public static function assignmentNotFound(string $assignmentId): self
    {
        return new self("Assignment not found with ID: {$assignmentId}");
    }

    /**
     * Create exception for quiz not found.
     */
    public static function quizNotFound(string $quizId): self
    {
        return new self("Quiz not found with ID: {$quizId}");
    }

    /**
     * Create exception for quiz attempt not found.
     */
    public static function quizAttemptNotFound(string $attemptId): self
    {
        return new self("Quiz attempt not found with ID: {$attemptId}");
    }

    /**
     * Create exception for submission not found.
     */
    public static function submissionNotFound(string $submissionId): self
    {
        return new self("Submission not found with ID: {$submissionId}");
    }

    /**
     * Create exception for assignment deadline passed.
     */
    public static function assignmentDeadlinePassed(string $assignmentId): self
    {
        return new self("Assignment {$assignmentId} deadline has passed");
    }

    /**
     * Create exception for quiz not available.
     */
    public static function quizNotAvailable(string $quizId): self
    {
        return new self("Quiz {$quizId} is not available for attempts");
    }

    /**
     * Create exception for maximum attempts exceeded.
     */
    public static function maxAttemptsExceeded(string $quizId, string $studentId): self
    {
        return new self("Student {$studentId} has exceeded maximum attempts for quiz {$quizId}");
    }

    /**
     * Create exception for quiz attempt already completed.
     */
    public static function attemptAlreadyCompleted(string $attemptId): self
    {
        return new self("Quiz attempt {$attemptId} is already completed");
    }

    /**
     * Create exception for quiz attempt time expired.
     */
    public static function attemptTimeExpired(string $attemptId): self
    {
        return new self("Quiz attempt {$attemptId} time has expired");
    }

    /**
     * Create exception for invalid score.
     */
    public static function invalidScore(float $score, float $maxScore): self
    {
        return new self("Invalid score {$score}. Score must be between 0 and {$maxScore}");
    }

    /**
     * Create exception for unauthorized access.
     */
    public static function unauthorized(string $userId, string $action): self
    {
        return new self("User {$userId} is not authorized to perform action: {$action}");
    }

    /**
     * Create exception for student not enrolled.
     */
    public static function studentNotEnrolled(string $studentId, string $courseId): self
    {
        return new self("Student {$studentId} is not enrolled in course {$courseId}");
    }

    /**
     * Create exception for duplicate submission.
     */
    public static function duplicateSubmission(string $assignmentId, string $studentId): self
    {
        return new self("Student {$studentId} has already submitted assignment {$assignmentId}");
    }

    /**
     * Create exception for invalid question data.
     */
    public static function invalidQuestionData(string $reason): self
    {
        return new self("Invalid question data: {$reason}");
    }

    /**
     * Create exception for missing answers.
     */
    public static function missingAnswers(array $missingQuestionIds): self
    {
        $questionList = implode(', ', $missingQuestionIds);

        return new self("Missing answers for questions: {$questionList}");
    }

    /**
     * Create exception for service operation failure.
     */
    public static function operationFailed(string $operation, string $reason = ''): self
    {
        $message = "Assessment service operation '{$operation}' failed";
        if ($reason) {
            $message .= ": {$reason}";
        }

        return new self($message);
    }

    /**
     * Create exception for database transaction failure.
     */
    public static function transactionFailed(string $operation): self
    {
        return new self("Database transaction failed during: {$operation}");
    }

    /**
     * Create exception for file upload failure.
     */
    public static function fileUploadFailed(string $filename, string $reason = ''): self
    {
        $message = "Failed to upload file: {$filename}";
        if ($reason) {
            $message .= ". Reason: {$reason}";
        }

        return new self($message);
    }
}
