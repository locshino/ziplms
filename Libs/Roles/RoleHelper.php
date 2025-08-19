<?php

namespace App\Libs\Roles;

use App\Enums\System\RoleSystem;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * RoleHelper
 *
 * A static helper class for role-related operations and checks.
 * Provides convenient static methods to check user roles without repeating logic.
 */
class RoleHelper
{
    /**
     * Check if the current authenticated user is a super admin.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function isSuperAdmin(?Authenticatable $user = null): bool
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return false;
        }

        $superAdminRole = config('filament-shield.super_admin.name', RoleSystem::SUPER_ADMIN->value);

        return method_exists($user, 'hasRole') ? $user->hasRole($superAdminRole) : false;
    }

    /**
     * Check if the current authenticated user is an admin.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function isAdmin(?Authenticatable $user = null): bool
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole(RoleSystem::ADMIN->value) : false;
    }

    /**
     * Check if the current authenticated user is a manager.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function isManager(?Authenticatable $user = null): bool
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole(RoleSystem::MANAGER->value) : false;
    }

    /**
     * Check if the current authenticated user is a teacher.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function isTeacher(?Authenticatable $user = null): bool
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole(RoleSystem::TEACHER->value) : false;
    }

    /**
     * Check if the current authenticated user is a student.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function isStudent(?Authenticatable $user = null): bool
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole(RoleSystem::STUDENT->value) : false;
    }

    /**
     * Check if the current authenticated user has any of the specified roles.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function hasAnyRole(array|string $roles, ?Authenticatable $user = null): bool
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasAnyRole') ? $user->hasAnyRole($roles) : false;
    }

    /**
     * Check if the current authenticated user has all of the specified roles.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function hasAllRoles(array|string $roles, ?Authenticatable $user = null): bool
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasAllRoles') ? $user->hasAllRoles($roles) : false;
    }

    /**
     * Check if the current authenticated user has a specific role.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function hasRole(string $role, ?Authenticatable $user = null): bool
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole($role) : false;
    }

    /**
     * Get all roles of the current authenticated user.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function getRoles(?Authenticatable $user = null): Collection
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return new Collection;
        }

        return $user->roles ?? new Collection;
    }

    /**
     * Check if the current authenticated user can modify system roles.
     * Only super admin can modify system roles.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function canModifySystemRoles(?Authenticatable $user = null): bool
    {
        return static::isSuperAdmin($user);
    }

    /**
     * Check if the current authenticated user has administrative privileges.
     * This includes super admin, admin, and manager roles.
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function isAdministrative(?Authenticatable $user = null): bool
    {
        return static::hasAnyRole([
            RoleSystem::SUPER_ADMIN->value,
            RoleSystem::ADMIN->value,
            RoleSystem::MANAGER->value,
        ], $user);
    }

    /**
     * Get the highest role of the current authenticated user.
     * Returns the role with the highest priority (super_admin > admin > manager > teacher > student).
     *
     * @param  Authenticatable|null  $user  Optional user instance, defaults to current authenticated user
     */
    public static function getHighestRole(?Authenticatable $user = null): ?string
    {
        $user = $user ?? static::getCurrentUser();

        if (! $user) {
            return null;
        }

        $roleHierarchy = [
            RoleSystem::SUPER_ADMIN->value,
            RoleSystem::ADMIN->value,
            RoleSystem::MANAGER->value,
            RoleSystem::TEACHER->value,
            RoleSystem::STUDENT->value,
        ];

        foreach ($roleHierarchy as $role) {
            if (method_exists($user, 'hasRole') ? $user->hasRole($role) : false) {
                return $role;
            }
        }

        return null;
    }

    /**
     * Check if a role is a system role.
     */
    public static function isSystemRole(string $roleName): bool
    {
        $systemRoles = [
            RoleSystem::SUPER_ADMIN->value,
            RoleSystem::ADMIN->value,
            RoleSystem::MANAGER->value,
            RoleSystem::TEACHER->value,
            RoleSystem::STUDENT->value,
        ];

        return in_array($roleName, $systemRoles);
    }

    /**
     * Get all system role names.
     */
    public static function getSystemRoles(): array
    {
        return [
            RoleSystem::SUPER_ADMIN->value,
            RoleSystem::ADMIN->value,
            RoleSystem::MANAGER->value,
            RoleSystem::TEACHER->value,
            RoleSystem::STUDENT->value,
        ];
    }

    /**
     * Get the current authenticated user.
     * 
     * @return Authenticatable|null The current authenticated user or null if not authenticated
     */
    public static function getCurrentUser(): ?Authenticatable
    {
        try {
            return Auth::user();
        } catch (\Exception $e) {
            return null;
        }
    }
}
