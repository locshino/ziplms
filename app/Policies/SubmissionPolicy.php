<?php

namespace App\Policies;

use App\Models\Submission;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubmissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_submission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Submission $submission): bool
    {
        // Super admin and admin can view all submissions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return true;
        }
        
        // Teachers can view submissions in assignments/courses they teach
        if (RoleHelper::isTeacher($user) && $submission->assignment && $submission->assignment->course && $submission->assignment->course->teachers()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        // Students can view their own submissions
        if (RoleHelper::isStudent($user) && $submission->user_id === $user->id) {
            return true;
        }
        
        return $user->can('view_submission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_submission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Submission $submission): bool
    {
        // Super admin and admin can update all submissions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('update_submission');
        }
        
        // Teachers can update submissions in assignments/courses they teach (for grading)
        if (RoleHelper::isTeacher($user) && $submission->assignment && $submission->assignment->course && $submission->assignment->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('update_submission');
        }
        
        // Students can update their own submissions (if not yet graded)
        if (RoleHelper::isStudent($user) && $submission->user_id === $user->id && !$submission->is_graded) {
            return $user->can('update_submission');
        }
        
        return $user->can('update_submission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Submission $submission): bool
    {
        // Super admin and admin can delete all submissions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_submission');
        }
        
        // Teachers can delete submissions in assignments/courses they teach
        if (RoleHelper::isTeacher($user) && $submission->assignment && $submission->assignment->course && $submission->assignment->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('delete_submission');
        }
        
        // Students can delete their own submissions (if not yet graded)
        if (RoleHelper::isStudent($user) && $submission->user_id === $user->id && !$submission->is_graded) {
            return $user->can('delete_submission');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_submission');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Submission $submission): bool
    {
        return $user->can('force_delete_submission');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_submission');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Submission $submission): bool
    {
        return $user->can('restore_submission');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_submission');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Submission $submission): bool
    {
        return $user->can('replicate_submission');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_submission');
    }
}
