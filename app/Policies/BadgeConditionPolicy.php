<?php

namespace App\Policies;

use App\Models\User;
use App\Models\BadgeCondition;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class BadgeConditionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_badge::condition');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BadgeCondition $badgeCondition): bool
    {
        return $user->can('view_badge::condition');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin, admin, and manager can create badge conditions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isManager($user)) {
            return $user->can('create_badge::condition');
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BadgeCondition $badgeCondition): bool
    {
        // Only super admin, admin, and manager can update badge conditions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isManager($user)) {
            return $user->can('update_badge::condition');
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BadgeCondition $badgeCondition): bool
    {
        // Only super admin and admin can delete badge conditions
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_badge::condition');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_badge::condition');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, BadgeCondition $badgeCondition): bool
    {
        return $user->can('force_delete_badge::condition');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_badge::condition');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, BadgeCondition $badgeCondition): bool
    {
        return $user->can('restore_badge::condition');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_badge::condition');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, BadgeCondition $badgeCondition): bool
    {
        return $user->can('replicate_badge::condition');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_badge::condition');
    }
}
