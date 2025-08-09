<?php

namespace App\Policies;

use App\Models\User;
use App\Models\QuizAttempt;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuizAttemptPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_quiz::attempt');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, QuizAttempt $quizAttempt): bool
    {
        // Super admin and admin can view all quiz attempts
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return true;
        }
        
        // Teachers can view quiz attempts in courses they teach
        if (RoleHelper::isTeacher($user) && $quizAttempt->quiz && $quizAttempt->quiz->course && $quizAttempt->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        // Students can view their own quiz attempts
        if (RoleHelper::isStudent($user) && $quizAttempt->user_id === $user->id) {
            return true;
        }
        
        return $user->can('view_quiz::attempt');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Super admin, admin, and students can create quiz attempts
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isStudent($user)) {
            return $user->can('create_quiz::attempt');
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, QuizAttempt $quizAttempt): bool
    {
        // Super admin and admin can update all quiz attempts
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('update_quiz::attempt');
        }
        
        // Teachers can update quiz attempts in courses they teach (for grading)
        if (RoleHelper::isTeacher($user) && $quizAttempt->quiz && $quizAttempt->quiz->course && $quizAttempt->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('update_quiz::attempt');
        }
        
        // Students can update their own quiz attempts (if not completed)
        if (RoleHelper::isStudent($user) && $quizAttempt->user_id === $user->id && !$quizAttempt->completed_at) {
            return $user->can('update_quiz::attempt');
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, QuizAttempt $quizAttempt): bool
    {
        // Super admin and admin can delete all quiz attempts
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_quiz::attempt');
        }
        
        // Teachers can delete quiz attempts in courses they teach
        if (RoleHelper::isTeacher($user) && $quizAttempt->quiz && $quizAttempt->quiz->course && $quizAttempt->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('delete_quiz::attempt');
        }
        
        // Students can delete their own quiz attempts (if not completed)
        if (RoleHelper::isStudent($user) && $quizAttempt->user_id === $user->id && !$quizAttempt->completed_at) {
            return $user->can('delete_quiz::attempt');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_quiz::attempt');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, QuizAttempt $quizAttempt): bool
    {
        return $user->can('force_delete_quiz::attempt');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_quiz::attempt');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, QuizAttempt $quizAttempt): bool
    {
        return $user->can('restore_quiz::attempt');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_quiz::attempt');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, QuizAttempt $quizAttempt): bool
    {
        return $user->can('replicate_quiz::attempt');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_quiz::attempt');
    }
}
