<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserBadge;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserBadgePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view_any_user::badge');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserBadge $userBadge): bool
    {
        // Super admin and admin can view all user badges
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return true;
        }
        
        // Users can view their own badges
        if ($userBadge->user_id === $user->id) {
            return true;
        }
        
        return $user->can('view_user::badge');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin, admin, and manager can create user badges
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isManager($user)) {
            return $user->can('create_user::badge');
        }
        
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserBadge $userBadge): bool
    {
        // Only super admin, admin, and manager can update user badges
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user) || RoleHelper::isManager($user)) {
            return $user->can('update_user::badge');
        }
        
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserBadge $userBadge): bool
    {
        // Only super admin and admin can delete user badges
        if (RoleHelper::isSuperAdmin($user) || RoleHelper::isAdmin($user)) {
            return $user->can('delete_user::badge');
        }
        
        return false;
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_user::badge');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, UserBadge $userBadge): bool
    {
        return $user->can('force_delete_user::badge');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_user::badge');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, UserBadge $userBadge): bool
    {
        return $user->can('restore_user::badge');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_user::badge');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, UserBadge $userBadge): bool
    {
        return $user->can('replicate_user::badge');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_user::badge');
    }
}
