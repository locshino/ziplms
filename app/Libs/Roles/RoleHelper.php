<?php

namespace App\Libs\Roles;

use App\Enums\System\RoleSystem as RoleSystemEnum;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * RoleHelper
 *
 * Static utility class for role-based operations and checks.
 * Provides optimized static methods for role validation and retrieval.
 */
class RoleHelper
{
    /**
     * Safely resolve the user instance.
     *
     * @param  Authenticatable|null  $user  Optional user, defaults to currently authenticated user.
     * @return Authenticatable|null
     */
    protected static function resolveUser(?Authenticatable $user = null): ?Authenticatable
    {
        if ($user !== null) {
            return $user;
        }
        try {
            return Auth::user();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Check if a user has a specific role by role value.
     *
     * @param  string  $role
     * @param  Authenticatable|null  $user
     * @return bool
     */
    protected static function checkRole(string $role, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);
        return $user && method_exists($user, 'hasRole') && $user->hasRole($role);
    }

    /**
     * Check if a user has any of the specified roles.
     *
     * @param  array|string  $roles
     * @param  Authenticatable|null  $user
     * @return bool
     */
    protected static function checkAnyRole(array|string $roles, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);
        return $user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles);
    }

    /**
     * Check if a user has all of the specified roles.
     *
     * @param  array|string  $roles
     * @param  Authenticatable|null  $user
     * @return bool
     */
    protected static function checkAllRoles(array|string $roles, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);
        return $user && method_exists($user, 'hasAllRoles') && $user->hasAllRoles($roles);
    }

    /**
     * Get all roles of the user.
     *
     * @param  Authenticatable|null  $user
     * @return Collection
     */
    public static function getRoles(?Authenticatable $user = null): Collection
    {
        /**
         * @var \App\Models\User $user
         */
        $user = static::resolveUser($user);
        return $user && property_exists($user, 'roles') ? $user->roles : new Collection;
    }

    /**
     * Check if the user is a super admin.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function isSuperAdmin(?Authenticatable $user = null): bool
    {
        $superAdminRole = config('filament-shield.super_admin.name', RoleSystemEnum::SUPER_ADMIN->value);
        return static::checkRole($superAdminRole, $user);
    }

    /**
     * Check if the user is an admin.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function isAdmin(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::ADMIN->value, $user);
    }

    /**
     * Check if the user is a manager.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function isManager(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::MANAGER->value, $user);
    }

    /**
     * Check if the user is a teacher.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function isTeacher(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::TEACHER->value, $user);
    }

    /**
     * Check if the user is a student.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function isStudent(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::STUDENT->value, $user);
    }

    /**
     * Check if the user has any of the specified roles.
     *
     * @param  array|string  $roles
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function hasAnyRole(array|string $roles, ?Authenticatable $user = null): bool
    {
        return static::checkAnyRole($roles, $user);
    }

    /**
     * Check if the user has all of the specified roles.
     *
     * @param  array|string  $roles
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function hasAllRoles(array|string $roles, ?Authenticatable $user = null): bool
    {
        return static::checkAllRoles($roles, $user);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param  string  $role
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function hasRole(string $role, ?Authenticatable $user = null): bool
    {
        return static::checkRole($role, $user);
    }

    /**
     * Check if the user can modify system roles.
     * Only super admin can modify system roles.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function canModifySystemRoles(?Authenticatable $user = null): bool
    {
        return static::isSuperAdmin($user);
    }

    /**
     * Check if the user has administrative privileges.
     * Includes super admin and admin roles.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function isAdministrative(?Authenticatable $user = null): bool
    {
        return static::hasAnyRole([
            RoleSystemEnum::SUPER_ADMIN->value,
            RoleSystemEnum::ADMIN->value,
        ], $user);
    }

    /**
     * Check if the user has management privileges.
     * Includes super admin, admin, and manager roles.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function isManageable(?Authenticatable $user = null): bool
    {
        return static::hasAnyRole([
            RoleSystemEnum::SUPER_ADMIN->value,
            RoleSystemEnum::ADMIN->value,
            RoleSystemEnum::MANAGER->value,
        ], $user);
    }

    /**
     * Check if the user is a LMS user.
     *
     * @param  Authenticatable|null  $user
     * @return bool
     */
    public static function isLMSUsers(?Authenticatable $user = null): bool
    {
        return static::hasAnyRole(static::getSystemRoles(), $user);
    }

    /**
     * Get the user's highest role based on role hierarchy.
     * Returns the role name with the highest priority (super_admin > admin > manager > teacher > student).
     *
     * @param  Authenticatable|null  $user
     * @return string|null
     */
    public static function getHighestRole(?Authenticatable $user = null): ?string
    {
        $user = static::resolveUser($user);
        if (! $user) {
            return null;
        }

        $roleHierarchy = [
            RoleSystemEnum::SUPER_ADMIN->value,
            RoleSystemEnum::ADMIN->value,
            RoleSystemEnum::MANAGER->value,
            RoleSystemEnum::TEACHER->value,
            RoleSystemEnum::STUDENT->value,
        ];

        foreach ($roleHierarchy as $role) {
            if (method_exists($user, 'hasRole') && $user->hasRole($role)) {
                return $role;
            }
        }

        return null;
    }

    /**
     * Check if a role name is a system role.
     *
     * @param  string  $roleName
     * @return bool
     */
    public static function isSystemRole(string $roleName): bool
    {
        return in_array($roleName, static::getSystemRoles());
    }

    /**
     * Get all system role names.
     *
     * @return array
     */
    public static function getSystemRoles(): array
    {
        return [
            RoleSystemEnum::SUPER_ADMIN->value,
            RoleSystemEnum::ADMIN->value,
            RoleSystemEnum::MANAGER->value,
            RoleSystemEnum::TEACHER->value,
            RoleSystemEnum::STUDENT->value,
        ];
    }
}
