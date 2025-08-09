<?php

namespace App\Repositories\Eloquent;

use App\Models\Assignment;
use App\Models\Submission;
use App\Repositories\Interfaces\AssignmentRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AssignmentRepository extends EloquentRepository implements AssignmentRepositoryInterface
{
    protected function model(): string
    {
        return Assignment::class;
    }

    /**
     * FIX: Changed type hint for $studentId from int to string to support UUIDs.
     */
    public function getStudentAssignments(
        string $studentId,
        ?string $courseId,
        ?string $filter,
        ?string $search
    ): LengthAwarePaginator {
        $user = Auth::user();
        $query = Assignment::query();

        if ($user->hasRole('student')) {
            $enrolledCourseIds = $user->enrollments()->pluck('course_id');
            if ($enrolledCourseIds->isEmpty()) {
                return Assignment::query()->whereRaw('1 = 0')->paginate();
            }
            $query->whereIn('course_id', $enrolledCourseIds);
        }

        $query->with(['course', 'submissions' => function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        }]);

        if ($courseId) {
            $query->where('course_id', $courseId);
        }

        if ($search) {
            $query->where('title', 'like', '%' . $search . '%');
        }

        $this->applyStatusFilter($query, $studentId, $filter);

        return $query->orderBy('due_at', 'desc')->paginate(10);
    }

    /**
     * FIX: Changed type hint for $studentId from int to string to support UUIDs.
     */
    private function applyStatusFilter(Builder $query, string $studentId, ?string $filter): void
    {
        if (!$filter || $filter === 'all') {
            return;
        }
        $now = Carbon::now();
        match ($filter) {
            'submitted' => $query->whereHas('submissions', fn ($q) => $q->where('student_id', $studentId)),
            'overdue' => $query->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $studentId))
                                ->where('due_at', '<', $now),
            'not_submitted' => $query->whereDoesntHave('submissions', fn ($q) => $q->where('student_id', $studentId))
                                     ->where('due_at', '>=', $now),
            default => null,
        };
    }

    /**
     * Create a new submission record for an assignment.
     * FIX: Changed type hint for $studentId from int to string to support UUIDs.
     */
    public function createSubmission(string $assignmentId, string $studentId): Model
    {
        return Submission::create([
            'assignment_id' => $assignmentId,
            'student_id' => $studentId,
        ]);
    }
}
