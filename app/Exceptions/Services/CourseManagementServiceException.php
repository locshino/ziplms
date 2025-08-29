<?php

namespace App\Exceptions\Services;

use Exception;

/**
 * Exception class for Course Management Service operations.
 */
class CourseManagementServiceException extends Exception
{
    /**
     * Create exception for invalid instructor.
     */
    public static function invalidInstructor(string $instructorId): self
    {
        return new self("Invalid instructor with ID: {$instructorId}");
    }

    /**
     * Create exception for invalid student.
     */
    public static function invalidStudent(string $studentId): self
    {
        return new self("Invalid student with ID: {$studentId}");
    }

    /**
     * Create exception for course not found.
     */
    public static function courseNotFound(string $courseId): self
    {
        return new self("Course not found with ID: {$courseId}");
    }

    /**
     * Create exception for already enrolled student.
     */
    public static function alreadyEnrolled(string $studentId, string $courseId): self
    {
        return new self("Student {$studentId} is already enrolled in course {$courseId}");
    }

    /**
     * Create exception for enrollment capacity exceeded.
     */
    public static function enrollmentCapacityExceeded(string $courseId): self
    {
        return new self("Enrollment capacity exceeded for course: {$courseId}");
    }

    /**
     * Create exception for unauthorized access.
     */
    public static function unauthorized(string $userId, string $action): self
    {
        return new self("User {$userId} is not authorized to perform action: {$action}");
    }

    /**
     * Create exception for course not active.
     */
    public static function courseNotActive(string $courseId): self
    {
        return new self("Course {$courseId} is not active for enrollment");
    }

    /**
     * Create exception for service operation failure.
     */
    public static function operationFailed(string $operation, string $reason = ''): self
    {
        $message = "Service operation '{$operation}' failed";
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
}
