<?php

namespace App\Policies;

use App\Libs\Roles\RoleHelper;
use App\Models\Course;
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
        return $user->can('view_any_courses::course');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Course $course): bool
    {
        $isCanView = $user->can('view_courses::course');
        if ($isCanView == false) {
            return false;
        }

        $isManagerCanView = $this->handleManagerPermissions($user, $course);
        if ($isManagerCanView !== null) {
            return $isManagerCanView;
        }

        return $isCanView;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_courses::course');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Course $course): bool
    {
        $isCanUpdate = $user->can('update_courses::course');

        if ($isCanUpdate == false) {
            return false;
        }

        $isManagerCanView = $this->handleManagerPermissions($user, $course);
        if ($isManagerCanView !== null) {
            return $isManagerCanView;
        }

        return $isCanUpdate;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Course $course): bool
    {
        $isCanDelete = $user->can('delete_courses::course');

        if ($isCanDelete == false) {
            return false;
        }

        $isManagerCanView = $this->handleManagerPermissions($user, $course);
        if ($isManagerCanView !== null) {
            return $isManagerCanView;
        }

        return $isCanDelete;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_courses::course');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Course $course): bool
    {
        $isCanForceDelete = $user->can('force_delete_courses::course');

        if ($isCanForceDelete == false) {
            return false;
        }

        $isManagerCanView = $this->handleManagerPermissions($user, $course);
        if ($isManagerCanView !== null) {
            return $isManagerCanView;
        }

        return $isCanForceDelete;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_courses::course');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Course $course): bool
    {
        $isCanRestore = $user->can('restore_courses::course');

        if ($isCanRestore == false) {
            return false;
        }

        $isManagerCanView = $this->handleManagerPermissions($user, $course);
        if ($isManagerCanView !== null) {
            return $isManagerCanView;
        }

        return $isCanRestore;
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_courses::course');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Course $course): bool
    {
        $isCanReplicate = $user->can('replicate_courses::course');

        if ($isCanReplicate == false) {
            return false;
        }

        $isManagerCanView = $this->handleManagerPermissions($user, $course);
        if ($isManagerCanView !== null) {
            return $isManagerCanView;
        }

        return $isCanReplicate;
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_courses::course');
    }

    private function handleManagerPermissions(User $user, Course $course): ?bool
    {
        return RoleHelper::isManager($user)
            ? $course->managers()->where('users.id', $user->id)->exists()
            : null;
    }
}
