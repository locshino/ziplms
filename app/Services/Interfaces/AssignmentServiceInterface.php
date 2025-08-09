<?php

namespace App\Services\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

/**
 * Interface for the Assignment Service.
 * Defines the contract for business logic operations related to assignments.
 */
interface AssignmentServiceInterface extends BaseServiceInterface
{
    /**
     * Get the paginated list of assignments for the currently authenticated student.
     */
    public function getAssignmentsForStudent(array $filters): LengthAwarePaginator;

    /**
     * Handle the submission of an assignment by a student.
     * FIX: Changed type hint for $studentId from int to string to support UUIDs.
     */
    public function submitAssignment(
        string $assignmentId,
        string $studentId,
        string $submissionType,
        array $data,
        ?UploadedFile $file
    ): Model;
}
