<?php

namespace App\Policies;

use App\Models\Course;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CoursePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_course');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        // Super admin and admin can view all courses
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return true;
        }
        
        // Teachers can view courses they are assigned to
        if (RoleHelper::isTeacher($user) && $course->teachers()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        // Students can view courses they are enrolled in
        if (RoleHelper::isStudent($user) && $course->enrollments()->where('user_id', $user->id)->exists()) {
            return true;
        }
        
        return $user->can('view_course');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_course');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        // Super admin and admin can update all courses
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('update_course');
        }
        
        // Teachers can update courses they are assigned to
        if (RoleHelper::isTeacher($user) && $course->teachers()->where('user_id', $user->id)->exists()) {
            return $user->can('update_course');
        }
        
        return $user->can('update_course');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        // Only super admin and admin can delete courses
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_course');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_course');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        return $user->can('force_delete_course');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_course');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Course $course): bool
    {
        return $user->can('restore_course');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_course');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Course $course): bool
    {
        return $user->can('replicate_course');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_course');
    }
}
