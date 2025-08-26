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
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_users::user');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user, User $userRecord): bool
    {
        $isCanView = $user->can('view_users::user');
        if ($isCanView == false) {
            return false;
        }

        $compare = RoleHelper::compareUserRoles($user, $userRecord);
        if ($compare === -1) return false;

        return $isCanView;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        return $user->can('create_users::user');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user, User $userRecord): bool
    {
        $isCanUpdate = $user->can('update_users::user');

        if ($isCanUpdate == false) {
            return false;
        }

        // Prevent lower role from updating higher role
        $compare = RoleHelper::compareUserRoles($user, $userRecord);
        if ($compare !== 1) return false;

        return $isCanUpdate;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function delete(User $user, User $userRecord): bool
    {
        $isCanDelete = $user->can('delete_users::user');

        if ($isCanDelete == false) {
            return false;
        }

        if ($user->id === $userRecord->id) return false;
        if (RoleHelper::isSuperAdmin($userRecord)) return false;

        return $isCanDelete;
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_users::user');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDelete(User $user, User $userRecord): bool
    {
        $isCanForceDelete = $user->can('force_delete_users::user');

        if ($isCanForceDelete == false) {
            return false;
        }

        if ($user->id === $userRecord->id) return false;
        if (RoleHelper::isSuperAdmin($userRecord)) return false;

        // Prevent lower role from force deleting higher role
        $compare = RoleHelper::compareUserRoles($user, $userRecord);
        if ($compare !== 1) return false;

        return $isCanForceDelete;
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_users::user');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restore(User $user, User $userRecord): bool
    {
        return $user->can('restore_users::user');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_users::user');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function replicate(User $user, User $userRecord): bool
    {
        return $user->can('replicate_users::user');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_users::user');
    }
}
