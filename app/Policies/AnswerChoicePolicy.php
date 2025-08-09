<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AnswerChoice;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerChoicePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_answer::choice');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AnswerChoice $answerChoice): bool
    {
        // Super admin and admin can view all answer choices
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return true;
        }
        
        // Teachers can view answer choices in questions they manage
        if (RoleHelper::isTeacher($user) && $answerChoice->question && $answerChoice->question->quiz && $answerChoice->question->quiz->course && $answerChoice->question->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        return $user->can('view_answer::choice');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin, admin, and teachers can create answer choices
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isTeacher($user)) {
            return $user->can('create_answer::choice');
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AnswerChoice $answerChoice): bool
    {
        // Super admin and admin can update all answer choices
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('update_answer::choice');
        }
        
        // Teachers can update answer choices in questions they manage
        if (RoleHelper::isTeacher($user) && $answerChoice->question && $answerChoice->question->quiz && $answerChoice->question->quiz->course && $answerChoice->question->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('update_answer::choice');
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AnswerChoice $answerChoice): bool
    {
        // Super admin and admin can delete all answer choices
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_answer::choice');
        }
        
        // Teachers can delete answer choices in questions they manage
        if (RoleHelper::isTeacher($user) && $answerChoice->question && $answerChoice->question->quiz && $answerChoice->question->quiz->course && $answerChoice->question->quiz->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('delete_answer::choice');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_answer::choice');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, AnswerChoice $answerChoice): bool
    {
        return $user->can('force_delete_answer::choice');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_answer::choice');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, AnswerChoice $answerChoice): bool
    {
        return $user->can('restore_answer::choice');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_answer::choice');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, AnswerChoice $answerChoice): bool
    {
        return $user->can('replicate_answer::choice');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_answer::choice');
    }
}
