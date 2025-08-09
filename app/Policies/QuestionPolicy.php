<?php

namespace App\Policies;

use App\Models\Question;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class QuestionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_question');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Question $question): bool
    {
        // Super admin and admin can view all questions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return true;
        }
        
        // Teachers can view questions in quizzes they manage
        if (RoleHelper::isTeacher($user) && $question->quiz && $question->quiz->course && $question->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        return $user->can('view_question');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin, admin, and teachers can create questions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isTeacher($user)) {
            return $user->can('create_question');
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Question $question): bool
    {
        // Super admin and admin can update all questions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('update_question');
        }
        
        // Teachers can update questions in quizzes they manage
        if (RoleHelper::isTeacher($user) && $question->quiz && $question->quiz->course && $question->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('update_question');
        }
        
        return $user->can('update_question');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Question $question): bool
    {
        // Super admin and admin can delete all questions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_question');
        }
        
        // Teachers can delete questions in quizzes they manage
        if (RoleHelper::isTeacher($user) && $question->quiz && $question->quiz->course && $question->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('delete_question');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_question');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Question $question): bool
    {
        return $user->can('force_delete_question');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_question');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Question $question): bool
    {
        return $user->can('restore_question');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_question');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Question $question): bool
    {
        return $user->can('replicate_question');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_question');
    }
}
