<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Assignment;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_assignment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Assignment $assignment): bool
    {
        // Super admin and admin can view all assignments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->assignment()->all()->build());
        }
        
        // Teachers can view assignments in courses they teach
        if (RoleHelper::isTeacher($user) && $assignment->course && $assignment->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can(PermissionHelper::make()->view()->assignment()->assigned()->build());
        }
        
        // Students can view assignments in courses they are enrolled in
        if (RoleHelper::isStudent($user) && $assignment->course && $assignment->course->enrollments()->where('user_id', $user->id)->exists()) {
            return $user->can(PermissionHelper::make()->view()->assignment()->enrolled()->build());
        }
        
        return $user->can(PermissionHelper::make()->view()->assignment()->public()->build());
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Super admin, admin, and managers can create assignments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->create()->assignment()->all()->build());
        }
        
        // Teachers can create assignments in courses they teach
        if (RoleHelper::isTeacher($user)) {
            return $user->can(PermissionHelper::make()->create()->assignment()->assigned()->build());
        }
        
        return $user->can(PermissionHelper::make()->create()->assignment()->owner()->build());
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Assignment $assignment): bool
    {
        // Super admin and admin can update all assignments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->update()->assignment()->all()->build());
        }
        
        // Teachers can update assignments in courses they teach
        if (RoleHelper::isTeacher($user) && $assignment->course && $assignment->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can(PermissionHelper::make()->update()->assignment()->assigned()->build());
        }
        
        return $user->can(PermissionHelper::make()->update()->assignment()->owner()->build());
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Assignment $assignment): bool
    {
        // Super admin and admin can delete all assignments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->delete()->assignment()->all()->build());
        }
        
        // Teachers can delete assignments in courses they teach
        if (RoleHelper::isTeacher($user) && $assignment->course && $assignment->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can(PermissionHelper::make()->delete()->assignment()->assigned()->build());
        }
        
        return $user->can(PermissionHelper::make()->delete()->assignment()->owner()->build());
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_assignment');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Assignment $assignment): bool
    {
        return $user->can('force_delete_assignment');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_assignment');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Assignment $assignment): bool
    {
        return $user->can('restore_assignment');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_assignment');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Assignment $assignment): bool
    {
        return $user->can('replicate_assignment');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_assignment');
    }
}
