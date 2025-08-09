<?php

namespace App\Policies;

use App\Models\User;
use App\Models\StudentQuizAnswer;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class StudentQuizAnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_student::quiz::answer');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentQuizAnswer $studentQuizAnswer): bool
    {
        // Super admin and admin can view all student quiz answers
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return true;
        }
        
        // Teachers can view student quiz answers in courses they teach
        if (RoleHelper::isTeacher($user) && $studentQuizAnswer->quizAttempt && $studentQuizAnswer->quizAttempt->quiz && $studentQuizAnswer->quizAttempt->quiz->course && $studentQuizAnswer->quizAttempt->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        // Students can view their own quiz answers
        if (RoleHelper::isStudent($user) && $studentQuizAnswer->quizAttempt && $studentQuizAnswer->quizAttempt->user_id === $user->id) {
            return true;
        }
        
        return $user->can('view_student::quiz::answer');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Super admin, admin, and students can create student quiz answers
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isStudent($user)) {
            return $user->can('create_student::quiz::answer');
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentQuizAnswer $studentQuizAnswer): bool
    {
        // Super admin and admin can update all student quiz answers
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('update_student::quiz::answer');
        }
        
        // Students can update their own quiz answers (if quiz attempt is not completed)
        if (RoleHelper::isStudent($user) && $studentQuizAnswer->quizAttempt && $studentQuizAnswer->quizAttempt->user_id === $user->id && !$studentQuizAnswer->quizAttempt->completed_at) {
            return $user->can('update_student::quiz::answer');
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentQuizAnswer $studentQuizAnswer): bool
    {
        // Super admin and admin can delete all student quiz answers
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_student::quiz::answer');
        }
        
        // Teachers can delete student quiz answers in courses they teach
        if (RoleHelper::isTeacher($user) && $studentQuizAnswer->quizAttempt && $studentQuizAnswer->quizAttempt->quiz && $studentQuizAnswer->quizAttempt->quiz->course && $studentQuizAnswer->quizAttempt->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('delete_student::quiz::answer');
        }
        
        // Students can delete their own quiz answers (if quiz attempt is not completed)
        if (RoleHelper::isStudent($user) && $studentQuizAnswer->quizAttempt && $studentQuizAnswer->quizAttempt->user_id === $user->id && !$studentQuizAnswer->quizAttempt->completed_at) {
            return $user->can('delete_student::quiz::answer');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_student::quiz::answer');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, StudentQuizAnswer $studentQuizAnswer): bool
    {
        return $user->can('force_delete_student::quiz::answer');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_student::quiz::answer');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, StudentQuizAnswer $studentQuizAnswer): bool
    {
        return $user->can('restore_student::quiz::answer');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_student::quiz::answer');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, StudentQuizAnswer $studentQuizAnswer): bool
    {
        return $user->can('replicate_student::quiz::answer');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_student::quiz::answer');
    }
}
