<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\User;

class QuizAccessService
{
    public function __construct(
        private QuizService $quizService
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
            return $user->can('manage-course-'.$quiz->course_id);
        }

        // Teachers can view all quizzes for grading purposes
        if ($user->hasRole('teacher')) {
            return true;
        }

        // Students can only view quizzes in enrolled courses
        if ($user->hasRole('student')) {
            return $this->isEnrolledInCourse($user, $quiz->course_id);
        }

        return false;
    }

    /**
     * Check if user can take quiz
     */
    public function canTakeQuiz(User $user, Quiz $quiz): bool
    {
        // Check take_quizzes permission first
        if (! $user->can('take_quizzes')) {
            return false;
        }

        // Super admin can take any quiz
        if ($user->hasRole('super_admin')) {
            return true;
        }

        // Admin can take any quiz
        if ($user->hasRole('admin')) {
            return true;
        }

        // Only students can take quizzes (after admin checks)
        if (! $user->hasRole('student')) {
            return false;
        }

        // Check enrollment for students
        if (! $this->isStudentEnrolledInCourse($user->id, $quiz->course_id)) {
            return false;
        }

        // Use existing quiz service logic
        return $this->quizService->canTakeQuiz($quiz->id, $user->id);
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
            return $user->can('manage-course-'.$quiz->course_id);
        }

        // Teachers can view all quiz results for grading purposes
        if ($user->hasRole('teacher')) {
            return true;
        }

        // Students can only view their own results (but need view_reports permission)
        if ($user->hasRole('student')) {
            return $this->isEnrolledInCourse($user, $quiz->course_id);
        }

        return false;
    }

    /**
     * Check if student is enrolled in course
     */
    private function isStudentEnrolledInCourse(string $studentId, string $courseId): bool
    {
        return Enrollment::where('student_id', $studentId)
            ->where('course_id', $courseId)
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
            'is_enrolled' => $user->hasRole('student') ?
                $this->isStudentEnrolledInCourse($user->id, $quiz->course_id) : null,
        ];
    }
}
