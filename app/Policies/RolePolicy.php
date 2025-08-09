<?php

namespace App\Policies;

use App\Models\Role;
use App\Libs\Roles\RoleHelper;
use App\Libs\Permissions\PermissionHelper;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Super admin can view all roles
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->role()->all()->build());
        }
        
        // Admin can view non-system roles
        if (RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->role()->public()->build());
        }
        
        return $user->can('view_any_role');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        // Super admin can view all roles
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->role()->all()->build());
        }
        
        // Admin can view non-system roles
        if (RoleHelper::isAdmin($user) && !$role->is_system) {
            return $user->can(PermissionHelper::make()->view()->role()->public()->build());
        }
        
        return $user->can('view_role');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin can create roles
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->create()->role()->all()->build());
        }
        
        return $user->can('create_role');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): bool
    {
        // Only super admin can update system roles
        if ($role->is_system) {
            return RoleHelper::isSuperAdmin($user) && $user->can(PermissionHelper::make()->configure()->role()->all()->build());
        }
        
        // Super admin can update all roles
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->update()->role()->all()->build());
        }
        
        return $user->can('update_role');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): bool
    {
        // Only super admin can delete system roles
        if ($role->is_system) {
            return RoleHelper::isSuperAdmin($user) && $user->can(PermissionHelper::make()->configure()->role()->all()->build());
        }
        
        // Super admin can delete all roles
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->delete()->role()->all()->build());
        }
        
        return $user->can('delete_role');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_role');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        // Only super admin can force delete system roles
        if ($role->is_system) {
            return RoleHelper::isSuperAdmin($user) && $user->can(PermissionHelper::make()->configure()->role()->all()->build());
        }
        
        // Super admin can force delete all roles
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->delete()->role()->all()->build());
        }
        
        return $user->can('force_delete_role');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_role');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Role $role): bool
    {
        // Only super admin can restore system roles
        if ($role->is_system) {
            return RoleHelper::isSuperAdmin($user) && $user->can(PermissionHelper::make()->configure()->role()->all()->build());
        }
        
        // Super admin can restore all roles
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->restore()->role()->all()->build());
        }
        
        return $user->can('restore_role');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_role');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Role $role): bool
    {
        return $user->can('replicate_role');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_role');
    }
}
