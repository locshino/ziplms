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
     * Compare role hierarchy between two users.
     * Returns -1 if userA < userB, 0 if equal, 1 if userA > userB.
     * If userB is not provided, defaults to current authenticated user.
     *
     * @return int|null -1: userA lower, 0: equal, 1: userA higher, null: cannot compare
     */
    public static function compareUserRoles(?Authenticatable $userA, ?Authenticatable $userB = null): ?int
    {
        $userB = static::resolveUser($userB);
        $userA = static::resolveUser($userA);
        if (! $userA || ! $userB) {
            return null;
        }
        $roleHierarchy = [
            RoleSystemEnum::SUPER_ADMIN->value,
            RoleSystemEnum::ADMIN->value,
            RoleSystemEnum::MANAGER->value,
            RoleSystemEnum::TEACHER->value,
            RoleSystemEnum::STUDENT->value,
            'custom', // custom role là thấp nhất
        ];

        $roleA = static::getHighestRole($userA);
        $roleB = static::getHighestRole($userB);
        if ($roleA === null || $roleB === null) {
            return null;
        }
        $indexA = array_search($roleA, $roleHierarchy);
        $indexB = array_search($roleB, $roleHierarchy);
        if ($indexA === false || $indexB === false) {
            return null;
        }
        if ($indexA === $indexB) {
            return 0;
        }

        return $indexA < $indexB ? 1 : -1;
    }

    /**
     * Safely resolve the user instance.
     *
     * @param  Authenticatable|null  $user  Optional user, defaults to currently authenticated user.
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
     */
    protected static function checkRole(string $role, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);

        return $user && method_exists($user, 'hasRole') && $user->hasRole($role);
    }

    /**
     * Check if a user has any of the specified roles.
     */
    protected static function checkAnyRole(array|string $roles, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);

        return $user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles);
    }

    /**
     * Check if a user has all of the specified roles.
     */
    protected static function checkAllRoles(array|string $roles, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);

        return $user && method_exists($user, 'hasAllRoles') && $user->hasAllRoles($roles);
    }

    /**
     * Get all roles of the user.
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
     */
    public static function isSuperAdmin(?Authenticatable $user = null): bool
    {
        $superAdminRole = config('filament-shield.super_admin.name', RoleSystemEnum::SUPER_ADMIN->value);

        return static::checkRole($superAdminRole, $user);
    }

    /**
     * Check if the user is an admin.
     */
    public static function isAdmin(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::ADMIN->value, $user);
    }

    /**
     * Check if the user is a manager.
     */
    public static function isManager(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::MANAGER->value, $user);
    }

    /**
     * Check if the user is a teacher.
     */
    public static function isTeacher(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::TEACHER->value, $user);
    }

    /**
     * Check if the user is a student.
     */
    public static function isStudent(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::STUDENT->value, $user);
    }

    /**
     * Check if the user has any of the specified roles.
     */
    public static function hasAnyRole(array|string $roles, ?Authenticatable $user = null): bool
    {
        return static::checkAnyRole($roles, $user);
    }

    /**
     * Check if the user has all of the specified roles.
     */
    public static function hasAllRoles(array|string $roles, ?Authenticatable $user = null): bool
    {
        return static::checkAllRoles($roles, $user);
    }

    /**
     * Check if the user has a specific role.
     */
    public static function hasRole(string $role, ?Authenticatable $user = null): bool
    {
        return static::checkRole($role, $user);
    }

    /**
     * Check if the user can modify system roles.
     * Only super admin can modify system roles.
     */
    public static function canModifySystemRoles(?Authenticatable $user = null): bool
    {
        return static::isSuperAdmin($user);
    }

    /**
     * Check if the user has administrative privileges.
     * Includes super admin and admin roles.
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
     */
    public static function isLMSUsers(?Authenticatable $user = null): bool
    {
        return static::hasAnyRole(static::getSystemRoles(), $user);
    }

    /**
     * Get the user's highest role based on role hierarchy.
     * Returns the role name with the highest priority (super_admin > admin > manager > teacher > student).
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

        // Nếu user chỉ có custom role, trả về 'custom' (thấp nhất)
        $roles = static::getRoles($user);
        if ($roles->count() > 0) {
            return 'custom';
        }

        return null;
    }

    /**
     * Check if a role name is a system role.
     */
    public static function isSystemRole(?string $roleName): bool
    {
        if ($roleName === null) {
            return false;
        }

        return in_array($roleName, static::getSystemRoles());
    }

    /**
     * Get all system role names.
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

    /**
     * Get all system role names without super admin.
     */
    public static function getBaseSystemRoles($allowGetRoleHigher = false): array
    {
        $baseRoles = [
            RoleSystemEnum::ADMIN->value,
            RoleSystemEnum::MANAGER->value,
            RoleSystemEnum::TEACHER->value,
            RoleSystemEnum::STUDENT->value,
        ];

        if ($allowGetRoleHigher === false) {
            $user = static::resolveUser();
            if (! $user) {
                return [];
            }

            $userHighestRole = static::getHighestRole($user);
            if (! $userHighestRole || $userHighestRole === 'custom') {
                return [];
            }

            $roleHierarchy = static::getSystemRoles();
            $userRoleIndex = array_search($userHighestRole, $roleHierarchy);

            if ($userRoleIndex === false) {
                return [];
            }

            return array_values(array_filter($baseRoles, function ($role) use ($roleHierarchy, $userRoleIndex) {
                $roleIndex = array_search($role, $roleHierarchy);

                return $roleIndex !== false && $roleIndex >= $userRoleIndex;
            }));
        }

        return $baseRoles;
    }
}
