<?php

namespace App\Policies;

use App\Models\User;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
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
        // Super admin can view all users
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->user()->all()->build());
        }
        
        // Admin can view non-super-admin users
        if (RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->user()->admin()->build());
        }
        
        // Manager can view users in their department
        if (RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->view()->user()->department()->build());
        }
        
        return $user->can('view_any_user');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function view(User $user): bool
    {
        // Users can view themselves
        return $user->can(PermissionHelper::make()->view()->user()->self()->build()) || $user->can('view_user');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function create(User $user): bool
    {
        // Super admin can create all types of users
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->create()->user()->all()->build());
        }
        
        // Admin can create non-super-admin users
        if (RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->create()->user()->admin()->build());
        }
        
        // Manager can invite users to their department
        if (RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->invite()->user()->department()->build());
        }
        
        return $user->can('create_user');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return bool
     */
    public function update(User $user, User $targetUser): bool
    {
        // Super admin can update anyone
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->update()->user()->all()->build());
        }
        
        // Users can update themselves
        if ($user->id === $targetUser->id) {
            return $user->can(PermissionHelper::make()->update()->user()->self()->build());
        }
        
        // Admin can update non-super-admin users
        if (RoleHelper::isAdmin($user) && !RoleHelper::isSuperAdmin($targetUser)) {
            return $user->can(PermissionHelper::make()->update()->user()->admin()->build());
        }
        
        // Manager can update users in their department
        if (RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->update()->user()->department()->build());
        }
        
        return $user->can('update_user');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $targetUser
     * @return bool
     */
    public function delete(User $user, User $targetUser): bool
    {
        // Prevent users from deleting themselves
        if ($user->id === $targetUser->id) {
            return false;
        }
        
        // Super admin can delete anyone (except themselves)
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->delete()->user()->all()->build());
        }
        
        // Prevent deleting super admin users unless user is super admin
        if (RoleHelper::isSuperAdmin($targetUser)) {
            return false;
        }
        
        // Admin can delete non-super-admin users
        if (RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->delete()->user()->admin()->build());
        }
        
        // Manager can suspend users in their department
        if (RoleHelper::isManager($user)) {
            return $user->can(PermissionHelper::make()->suspend()->user()->department()->build());
        }
        
        return $user->can('delete_user');
    }

    /**
     * Determine whether the user can bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_user');
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDelete(User $user): bool
    {
        return $user->can('force_delete_user');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_user');
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restore(User $user): bool
    {
        return $user->can('restore_user');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_user');
    }

    /**
     * Determine whether the user can bulk restore.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function replicate(User $user): bool
    {
        return $user->can('replicate_user');
    }

    /**
     * Determine whether the user can reorder.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_user');
    }
}
