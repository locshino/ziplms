<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Enrollment;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class EnrollmentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Super admin and admin can view all enrollments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->enrollment()->all()->build());
        }
        
        // Manager can view all enrollments
        if (RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->view()->enrollment()->all()->build());
        }
        
        // Teachers can view enrollments in courses they teach
        if (RoleHelper::isTeacher($user)) {
            return $user->can(PermissionHelper::make()->view()->enrollment()->assigned()->build());
        }
        
        // Students can view their own enrollments
        if (RoleHelper::isStudent($user)) {
            return $user->can(PermissionHelper::make()->view()->enrollment()->self()->build());
        }
        
        return $user->can('view_any_enrollment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Enrollment $enrollment): bool
    {
        // Super admin and admin can view all enrollments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->enrollment()->all()->build());
        }
        
        // Manager can view all enrollments
        if (RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->view()->enrollment()->all()->build());
        }
        
        // Teachers can view enrollments in courses they teach
        if (RoleHelper::isTeacher($user) && $enrollment->course && $enrollment->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can(PermissionHelper::make()->view()->enrollment()->assigned()->build());
        }
        
        // Students can view their own enrollments
        if (RoleHelper::isStudent($user) && $enrollment->user_id === $user->id) {
            return $user->can(PermissionHelper::make()->view()->enrollment()->owner()->build());
        }
        
        return $user->can('view_enrollment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Super admin, admin and manager can create enrollments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->create()->enrollment()->all()->build());
        }
        
        // Teachers can create enrollments for courses they teach
        if (RoleHelper::isTeacher($user)) {
            return $user->can(PermissionHelper::make()->create()->enrollment()->assigned()->build());
        }
        
        // Students can enroll themselves
        if (RoleHelper::isStudent($user)) {
            return $user->can(PermissionHelper::make()->enroll()->course()->self()->build());
        }
        
        return $user->can('create_enrollment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Enrollment $enrollment): bool
    {
        // Super admin and admin can update all enrollments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->update()->enrollment()->all()->build());
        }
        
        // Manager can update all enrollments
        if (RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->update()->enrollment()->all()->build());
        }
        
        // Teachers can update enrollments in courses they teach
        if (RoleHelper::isTeacher($user) && $enrollment->course && $enrollment->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can(PermissionHelper::make()->update()->enrollment()->assigned()->build());
        }
        
        return $user->can('update_enrollment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Enrollment $enrollment): bool
    {
        // Super admin and admin can delete all enrollments
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->delete()->enrollment()->all()->build());
        }
        
        // Manager can delete all enrollments
        if (RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->delete()->enrollment()->all()->build());
        }
        
        // Teachers can delete enrollments in courses they teach
        if (RoleHelper::isTeacher($user) && $enrollment->course && $enrollment->course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can(PermissionHelper::make()->delete()->enrollment()->assigned()->build());
        }
        
        // Students can withdraw from their own enrollments
        if (RoleHelper::isStudent($user) && $enrollment->user_id === $user->id) {
            return $user->can(PermissionHelper::make()->withdraw()->course()->self()->build());
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_enrollment');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Enrollment $enrollment): bool
    {
        return $user->can('force_delete_enrollment');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_enrollment');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Enrollment $enrollment): bool
    {
        return $user->can('restore_enrollment');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_enrollment');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Enrollment $enrollment): bool
    {
        return $user->can('replicate_enrollment');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_enrollment');
    }
}
