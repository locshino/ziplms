<?php

namespace App\Policies;

use App\Libs\Roles\RoleHelper;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class BadgePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_badge');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Badge $badge): bool
    {
        return $user->can('view_badge');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin, admin, and managers can create badges
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isManager($user)) {
            return $user->can('create_badge');
        }

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Badge $badge): bool
    {
        // Only super admin, admin, and managers can update badges
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isManager($user)) {
            return $user->can('update_badge');
        }

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Badge $badge): bool
    {
        // Only super admin and admin can delete badges
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_badge');
        }

        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_badge');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Badge $badge): bool
    {
        return $user->can('force_delete_badge');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_badge');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Badge $badge): bool
    {
        return $user->can('restore_badge');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_badge');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Badge $badge): bool
    {
        return $user->can('replicate_badge');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_badge');
    }
}
