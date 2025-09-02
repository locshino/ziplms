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
     *
     * Example:
     * ```php
     * $comparison = RoleHelper::compareUserRoles($userA, $userB);
     * if ($comparison === 1) {
     *     echo 'User A has a higher role than User B';
     * }
     * ```
     *
     * @param  Authenticatable|null  $userA  The first user to compare.
     * @param  Authenticatable|null  $userB  The second user to compare. Defaults to the currently authenticated user.
     * @return int|null Returns -1 if userA has a lower role, 0 if roles are equal, 1 if userA has a higher role, or null if comparison is not possible.
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
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return Authenticatable|null The resolved user instance or null if resolution fails.
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
     * @param  string  $role  The role to check.
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user has the specified role, false otherwise.
     */
    protected static function checkRole(string $role, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);

        return $user && method_exists($user, 'hasRole') && $user->hasRole($role);
    }

    /**
     * Check if a user has any of the specified roles.
     *
     * @param  array|string  $roles  The roles to check.
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user has any of the specified roles, false otherwise.
     */
    protected static function checkAnyRole(array|string $roles, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);

        return $user && method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roles);
    }

    /**
     * Check if a user has all of the specified roles.
     *
     * @param  array|string  $roles  The roles to check.
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user has all of the specified roles, false otherwise.
     */
    protected static function checkAllRoles(array|string $roles, ?Authenticatable $user = null): bool
    {
        $user = static::resolveUser($user);

        return $user && method_exists($user, 'hasAllRoles') && $user->hasAllRoles($roles);
    }

    /**
     * Get all roles of the user.
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return Collection A collection of roles associated with the user.
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
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user is a super admin, false otherwise.
     */
    public static function isSuperAdmin(?Authenticatable $user = null): bool
    {
        $superAdminRole = config('filament-shield.super_admin.name', RoleSystemEnum::SUPER_ADMIN->value);

        return static::checkRole($superAdminRole, $user);
    }

    /**
     * Check if the user is an admin.
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user is an admin, false otherwise.
     */
    public static function isAdmin(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::ADMIN->value, $user);
    }

    /**
     * Check if the user is a manager.
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user is a manager, false otherwise.
     */
    public static function isManager(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::MANAGER->value, $user);
    }

    /**
     * Check if the user is a teacher.
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user is a teacher, false otherwise.
     */
    public static function isTeacher(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::TEACHER->value, $user);
    }

    /**
     * Check if the user is a student.
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user is a student, false otherwise.
     */
    public static function isStudent(?Authenticatable $user = null): bool
    {
        return static::checkRole(RoleSystemEnum::STUDENT->value, $user);
    }

    /**
     * Check if the user has any of the specified roles.
     *
     * @param  array|string  $roles  The roles to check.
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user has any of the specified roles, false otherwise.
     */
    public static function hasAnyRole(array|string $roles, ?Authenticatable $user = null): bool
    {
        return static::checkAnyRole($roles, $user);
    }

    /**
     * Check if the user has all of the specified roles.
     *
     * @param  array|string  $roles  The roles to check.
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user has all of the specified roles, false otherwise.
     */
    public static function hasAllRoles(array|string $roles, ?Authenticatable $user = null): bool
    {
        return static::checkAllRoles($roles, $user);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param  string  $role  The role to check.
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user has the specified role, false otherwise.
     */
    public static function hasRole(string $role, ?Authenticatable $user = null): bool
    {
        return static::checkRole($role, $user);
    }

    /**
     * Check if the user can modify system roles.
     * Only super admin can modify system roles.
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user can modify system roles, false otherwise.
     */
    public static function canModifySystemRoles(?Authenticatable $user = null): bool
    {
        return static::isSuperAdmin($user);
    }

    /**
     * Check if the user has administrative privileges.
     * Includes super admin and admin roles.
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user has administrative privileges, false otherwise.
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
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user has management privileges, false otherwise.
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
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return bool True if the user is a LMS user, false otherwise.
     */
    public static function isLMSUsers(?Authenticatable $user = null): bool
    {
        return static::hasAnyRole(static::getSystemRoles(), $user);
    }

    /**
     * Get the user's highest role based on role hierarchy.
     * Returns the role name with the highest priority (super_admin > admin > manager > teacher > student).
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return string|null The highest role of the user or null if no roles are found.
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
     *
     * @param  string|null  $roleName  The role name to check.
     * @return bool True if the role name is a system role, false otherwise.
     */
    public static function isSystemRole(?string $roleName): bool
    {
        if ($roleName === null) {
            return false;
        }

        return in_array($roleName, static::getSystemRoles());
    }

    /**
     * Check if a role name is a super admin role.
     *
     * @param  string|null  $roleName  The role name to check.
     * @return bool True if the role name is a super admin role, false otherwise.
     */
    public static function isSuperAdminRole(?string $roleName): bool
    {
        if ($roleName === null) {
            return false;
        }

        return $roleName === RoleSystemEnum::SUPER_ADMIN->value;
    }

    /**
     * Check if a role name is an admin role.
     *
     * @param  string|null  $roleName  The role name to check.
     * @return bool True if the role name is an admin role, false otherwise.
     */
    public static function isAdminRole(?string $roleName): bool
    {
        if ($roleName === null) {
            return false;
        }

        return $roleName === RoleSystemEnum::ADMIN->value;
    }

    /**
     * Check if a role name is a manager role.
     *
     * @param  string|null  $roleName  The role name to check.
     * @return bool True if the role name is a manager role, false otherwise.
     */
    public static function isManagerRole(?string $roleName): bool
    {
        if ($roleName === null) {
            return false;
        }

        return $roleName === RoleSystemEnum::MANAGER->value;
    }

    /**
     * Check if a role name is a teacher role.
     *
     * @param  string|null  $roleName  The role name to check.
     * @return bool True if the role name is a teacher role, false otherwise.
     */
    public static function isTeacherRole(?string $roleName): bool
    {
        if ($roleName === null) {
            return false;
        }

        return $roleName === RoleSystemEnum::TEACHER->value;
    }

    /**
     * Check if a role name is a student role.
     *
     * @param  string|null  $roleName  The role name to check.
     * @return bool True if the role name is a student role, false otherwise.
     */
    public static function isStudentRole(?string $roleName): bool
    {
        if ($roleName === null) {
            return false;
        }

        return $roleName === RoleSystemEnum::STUDENT->value;
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
            RoleSystemEnum::ADMIN->value => RoleSystemEnum::ADMIN->getLabel(),
            RoleSystemEnum::MANAGER->value => RoleSystemEnum::MANAGER->getLabel(),
            RoleSystemEnum::TEACHER->value => RoleSystemEnum::TEACHER->getLabel(),
            RoleSystemEnum::STUDENT->value => RoleSystemEnum::STUDENT->getLabel(),
        ];

        // if ($allowGetRoleHigher === false) {
        //     $user = static::resolveUser();
        //     if (! $user) {
        //         return [];
        //     }

        //     $userHighestRole = static::getHighestRole($user);
        //     if (! $userHighestRole || $userHighestRole === 'custom') {
        //         return [];
        //     }

        //     $roleHierarchy = static::getSystemRoles();
        //     $userRoleIndex = array_search($userHighestRole, $roleHierarchy);

        //     if ($userRoleIndex === false) {
        //         return [];
        //     }

        //     return array_values(array_filter($baseRoles, function ($role) use ($roleHierarchy, $userRoleIndex) {
        //         $roleIndex = array_search($role, $roleHierarchy);

        //         return $roleIndex !== false && $roleIndex >= $userRoleIndex;
        //     }));
        // }

        return $baseRoles;
    }

    /**
     * Get all roles higher than the user's highest role.
     *
     * Example:
     * ```php
     * $higherRoles = RoleHelper::getHigherRoles($user);
     * print_r($higherRoles);
     * ```
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return array An array of roles higher than the user's highest role.
     */
    public static function getHigherRoles(?Authenticatable $user = null): array
    {
        $user = static::resolveUser($user);
        if (! $user) {
            return [];
        }

        $userHighestRole = static::getHighestRole($user);
        if (! $userHighestRole || $userHighestRole === 'custom') {
            return [];
        }

        $roleHierarchy = static::getSystemRoles();
        $userRoleIndex = array_search($userHighestRole, $roleHierarchy, true);

        if ($userRoleIndex === false) {
            return [];
        }

        return array_slice($roleHierarchy, 0, $userRoleIndex);
    }

    /**
     * Get all roles lower than the user's highest role.
     *
     * Example:
     * ```php
     * $lowerRoles = RoleHelper::getLowerRoles($user);
     * print_r($lowerRoles);
     * ```
     *
     * @param  Authenticatable|null  $user  Optional user instance. Defaults to the currently authenticated user.
     * @return array An array of roles lower than the user's highest role.
     */
    public static function getLowerRoles(?Authenticatable $user = null): array
    {
        $user = static::resolveUser($user);
        if (! $user) {
            return [];
        }

        $userHighestRole = static::getHighestRole($user);
        if (! $userHighestRole || $userHighestRole === 'custom') {
            return [];
        }

        $roleHierarchy = static::getSystemRoles();
        $userRoleIndex = array_search($userHighestRole, $roleHierarchy, true);

        if ($userRoleIndex === false) {
            return [];
        }

        return array_slice($roleHierarchy, $userRoleIndex + 1);
    }
}
