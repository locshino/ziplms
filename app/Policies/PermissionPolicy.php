<?php

namespace App\Policies;

use App\Libs\Permissions\PermissionHelper;
use App\Libs\Roles\RoleHelper;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PermissionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Super admin can view all permissions
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->permission()->all()->build());
        }

        // Admin can view non-system permissions
        if (RoleHelper::isAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->permission()->public()->build());
        }

        return $user->can('view_any_permission');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Permission $permission): bool
    {
        // Super admin can view all permissions
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->view()->permission()->all()->build());
        }

        // Admin can view non-system permissions
        if (RoleHelper::isAdmin($user) && ! $permission->is_system) {
            return $user->can(PermissionHelper::make()->view()->permission()->public()->build());
        }

        return $user->can('view_permission');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only super admin can create permissions
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->create()->permission()->all()->build());
        }

        return $user->can('create_permission');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Permission $permission): bool
    {
        // Only super admin can update system permissions
        if ($permission->is_system) {
            return RoleHelper::isSuperAdmin($user) && $user->can(PermissionHelper::make()->configure()->permission()->all()->build());
        }

        // Super admin can update all permissions
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->update()->permission()->all()->build());
        }

        return $user->can('update_permission');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Permission $permission): bool
    {
        // Only super admin can delete system permissions
        if ($permission->is_system) {
            return RoleHelper::isSuperAdmin($user) && $user->can(PermissionHelper::make()->configure()->permission()->all()->build());
        }

        // Super admin can delete all permissions
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->delete()->permission()->all()->build());
        }

        return $user->can('delete_permission');
    }

    /**
     * Determine whether the user can bulk delete.
     */
    public function deleteAny(User $user): bool
    {
        return $user->can('delete_any_permission');
    }

    /**
     * Determine whether the user can permanently delete.
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        // Only super admin can force delete system permissions
        if ($permission->is_system) {
            return RoleHelper::isSuperAdmin($user) && $user->can(PermissionHelper::make()->configure()->permission()->all()->build());
        }

        // Super admin can force delete all permissions
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->delete()->permission()->all()->build());
        }

        return $user->can('force_delete_permission');
    }

    /**
     * Determine whether the user can permanently bulk delete.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->can('force_delete_any_permission');
    }

    /**
     * Determine whether the user can restore.
     */
    public function restore(User $user, Permission $permission): bool
    {
        // Only super admin can restore system permissions
        if ($permission->is_system) {
            return RoleHelper::isSuperAdmin($user) && $user->can(PermissionHelper::make()->configure()->permission()->all()->build());
        }

        // Super admin can restore all permissions
        if (RoleHelper::isSuperAdmin($user)) {
            return $user->can(PermissionHelper::make()->restore()->permission()->all()->build());
        }

        return $user->can('restore_permission');
    }

    /**
     * Determine whether the user can bulk restore.
     */
    public function restoreAny(User $user): bool
    {
        return $user->can('restore_any_permission');
    }

    /**
     * Determine whether the user can replicate.
     */
    public function replicate(User $user, Permission $permission): bool
    {
        return $user->can('replicate_permission');
    }

    /**
     * Determine whether the user can reorder.
     */
    public function reorder(User $user): bool
    {
        return $user->can('reorder_permission');
    }
}
