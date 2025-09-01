<?php

namespace App\Policies;

use App\Libs\Roles\RoleHelper;
use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        $isCanViewAny = $user->can('view_any_users::user');

        return $isCanViewAny;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, User $userRecord): bool
    {
        $isCanView = $user->can('view_users::user');

        if ($isCanView == false) {
            return false;
        }

        if (RoleHelper::isSuperAdmin($userRecord)) {
            return false;
        }

        $compare = RoleHelper::compareUserRoles($user, $userRecord);
        if ($compare === -1) {
            return false;
        }

        return $isCanView;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('create_users::user');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $userRecord): bool
    {
        $isCanUpdate = $user->can('update_users::user');

        if ($isCanUpdate == false) {
            return false;
        }

        // Prevent lower role from updating higher role
        $compare = RoleHelper::compareUserRoles($user, $userRecord);
        if ($compare !== 1) {
            return false;
        }

        return $isCanUpdate;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $userRecord): bool
    {
        $isCanDelete = $user->can('delete_users::user');

        if ($isCanDelete == false) {
            return false;
        }

        if ($user->id === $userRecord->id) {
            return false;
        }
        if (RoleHelper::isSuperAdmin($userRecord)) {
            return false;
        }

        return $isCanDelete;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        $isCanDeleteAny = $user->can('delete_any_users::user');
        if (app('impersonate')->isImpersonating()) {
            return false;
        }

        return $isCanDeleteAny;
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, User $userRecord): bool
    {
        $isCanForceDelete = $user->can('force_delete_users::user');

        if ($isCanForceDelete == false) {
            return false;
        }

        if (app('impersonate')->isImpersonating()) {
            return false;
        }

        if ($user->id === $userRecord->id) {
            return false;
        }
        if (RoleHelper::isSuperAdmin($userRecord)) {
            return false;
        }

        // Prevent lower role from force deleting higher role
        $compare = RoleHelper::compareUserRoles($user, $userRecord);
        if ($compare !== 1) {
            return false;
        }

        return $isCanForceDelete;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        if (app('impersonate')->isImpersonating()) {
            return false;
        }

        return $user->can('force_delete_any_users::user');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, User $userRecord): bool
    {
        return $user->can('restore_users::user');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_users::user');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function replicate(User $user, User $userRecord): bool
    {
        return $user->can('replicate_users::user');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_users::user');
    }
}
