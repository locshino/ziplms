<?php

namespace App\Services;

use App\Libs\Roles\RoleHelper;
use App\Models\Quiz;
use App\Models\User;
use App\Services\Interfaces\QuizAccessServiceInterface;
use App\Services\Interfaces\QuizServiceInterface;

class QuizAccessService implements QuizAccessServiceInterface
{
    public function __construct(
        private QuizServiceInterface $quizService
    ) {}

    /**
     * Check if user can view quiz
     */
    public function canViewQuiz(User $user, Quiz $quiz): bool
    {
        // Check basic view_quizzes permission first
        if (! $user->can('view_quizzes')) {
            return false;
        }

        // Super admin can view all quizzes
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Admin can view all quizzes
        if ($user->hasRole('admin')) {
            return true;
        }

        // Manager can only view quizzes in courses they have permission for
        if ($user->hasRole('manager')) {
            return $quiz->courses()->get()->some(function ($course) use ($user) {
                return $user->can('manage-course-'.$course->id);
            });
        }

        // Teachers can view all quizzes for grading purposes
        if ($user->hasRole('teacher')) {
            return true;
        }

        // Students can only view quizzes in enrolled courses
        if (RoleHelper::isStudent($user)) {
            return $this->isEnrolledInQuizCourses($user, $quiz);
        }

        return false;
    }

    /**
     * Check if user can take quiz
     * Simplified logic: check student role, quiz exists in course_quizzes with valid time
     */
    public function canTakeQuiz(User $user, Quiz $quiz): bool
    {
        // Check if user is a student
        if (! RoleHelper::isStudent($user)) {
            return false;
        }

        // Check if quiz exists in course_quizzes table with valid time restrictions
        $courseQuiz = $quiz->courses()
            ->withPivot(['start_at', 'end_at'])
            ->first();

        if (! $courseQuiz) {
            return false;
        }

        // Check time restrictions
        $now = now();
        $startAt = $courseQuiz->pivot->start_at;
        $endAt = $courseQuiz->pivot->end_at;

        // If start_at is set and current time is before start time
        if ($startAt && $now->lt($startAt)) {
            return false;
        }

        // If end_at is set and current time is after end time
        if ($endAt && $now->gt($endAt)) {
            return false;
        }

        return true;
    }

    /**
     * Check if user can manage quiz (create, update, delete)
     */
    public function canManageQuiz(User $user, ?Quiz $quiz = null, string $action = 'edit'): bool
    {
        // Check specific permission based on action
        $permissionMap = [
            'create' => 'create_quizzes',
            'edit' => 'edit_quizzes',
            'delete' => 'delete_quizzes',
            'view' => 'view_quizzes',
        ];

        $permission = $permissionMap[$action] ?? 'edit_quizzes';

        if (! $user->can($permission)) {
            return false;
        }

        // Super admin can manage all quizzes
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Admin can manage all quizzes
        if ($user->hasRole('admin')) {
            return true;
        }

        // Manager can only manage quizzes in courses they have permission for
        if ($user->hasRole('manager')) {
            if ($quiz) {
                return $user->can('manage-course-'.$quiz->course_id);
            }

            // For creating new quiz, we'll need course_id from request
            return true; // Will be validated in controller with course_id
        }

        // Teachers cannot manage quizzes (only view and grade)
        return false;
    }

    /**
     * Check if user can view quiz results
     */
    public function canViewResults(User $user, Quiz $quiz): bool
    {
        // Check view_reports permission first
        if (! $user->can('view_reports')) {
            return false;
        }

        // Super admin can view all results
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Admin can view all results
        if ($user->hasRole('admin')) {
            return true;
        }

        // Manager can view results for courses they have permission for
        if ($user->hasRole('manager')) {
            return $quiz->courses()->get()->some(function ($course) use ($user) {
                return $user->can('manage-course-'.$course->id);
            });
        }

        // Teachers can view all quiz results for grading purposes
        if ($user->hasRole('teacher')) {
            return true;
        }

        // Students can only view their own results (but need view_reports permission)
        if (RoleHelper::isStudent($user)) {
            return $this->isEnrolledInQuizCourses($user, $quiz);
        }

        return false;
    }

    /**
     * Check if user is enrolled in course through course_user pivot table
     */
    private function isEnrolledInCourse(User $user, string $courseId): bool
    {
        return $user->courses()->where('course_id', $courseId)->exists();
    }

    /**
     * Check if user is enrolled in any course that contains the quiz
     */
    public function isEnrolledInQuizCourses(User $user, Quiz $quiz): bool
    {
        return $quiz->courses()
            ->whereHas('users', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();
    }

    /**
     * Get quiz access summary for user
     */
    public function getQuizAccessSummary(User $user, Quiz $quiz): array
    {
        return [
            'can_view' => $this->canViewQuiz($user, $quiz),
            'can_take' => $this->canTakeQuiz($user, $quiz),
            'can_manage' => $this->canManageQuiz($user, $quiz),
            'can_view_results' => $this->canViewResults($user, $quiz),
            'is_enrolled' => RoleHelper::isStudent($user) ?
                $this->isEnrolledInQuizCourses($user, $quiz) : null,
        ];
    }

    /**
     * Check if user can edit quiz
     */
    public function canEditQuiz(User $user, Quiz $quiz): bool
    {
        return $this->canManageQuiz($user, $quiz);
    }

    /**
     * Check if user can delete quiz
     */
    public function canDeleteQuiz(User $user, Quiz $quiz): bool
    {
        return $this->canManageQuiz($user, $quiz);
    }

    /**
     * Check if user can view quiz results
     */
    public function canViewQuizResults(User $user, Quiz $quiz): bool
    {
        return $this->canViewResults($user, $quiz);
    }

    /**
     * Check if user can grade quiz
     */
    public function canGradeQuiz(User $user, Quiz $quiz): bool
    {
        // Teachers and admins can grade quizzes
        return $user->hasRole(['teacher', 'admin', 'super_admin']);
    }

    /**
     * Get accessible quizzes for user
     */
    public function getAccessibleQuizzes(User $user): \Illuminate\Database\Eloquent\Collection
    {
        if ($user->hasRole(['super_admin', 'admin'])) {
            return Quiz::all();
        }

        if ($user->hasRole('teacher')) {
            return Quiz::all(); // Teachers can view all for grading
        }

        if (RoleHelper::isStudent($user)) {
            return Quiz::whereHas('courses.users', function ($query) use ($user) {
                $query->where('users.id', $user->id);
            })->get();
        }

        return collect();
    }

    /**
     * Check if quiz is accessible at current time
     */
    public function isQuizAccessibleNow(Quiz $quiz): bool
    {
        $now = now();

        // Check if quiz has time restrictions
        if ($quiz->start_time && $now->lt($quiz->start_time)) {
            return false;
        }

        if ($quiz->end_time && $now->gt($quiz->end_time)) {
            return false;
        }

        return true;
    }

    /**
     * Check if user has permission for specific quiz action
     */
    public function hasQuizPermission(User $user, Quiz $quiz, string $permission): bool
    {
        return match ($permission) {
            'view' => $this->canViewQuiz($user, $quiz),
            'take' => $this->canTakeQuiz($user, $quiz),
            'edit' => $this->canEditQuiz($user, $quiz),
            'delete' => $this->canDeleteQuiz($user, $quiz),
            'manage' => $this->canManageQuiz($user, $quiz),
            'grade' => $this->canGradeQuiz($user, $quiz),
            'view_results' => $this->canViewQuizResults($user, $quiz),
            default => false,
        };
    }
}
