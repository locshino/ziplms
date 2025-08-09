<?php

namespace App\Repositories\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Model;

interface AssignmentRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get a paginated collection of assignments for a specific student.
     * FIX: Changed type hint for $studentId from int to string to support UUIDs.
     */
    public function getStudentAssignments(
        string $studentId,
        ?string $courseId,
        ?string $filter,
        ?string $search
    ): LengthAwarePaginator;

    /**
     * Create a new submission record for an assignment.
     * FIX: Changed type hint for $studentId from int to string to support UUIDs.
     */
    public function createSubmission(string $assignmentId, string $studentId): Model;
}
