<?php

namespace App\Libs\Roles;

use App\Enums\Roles\RoleSystemEnum;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * RoleHelper
 *
 * A static helper class for role-related operations and checks.
 * Provides convenient static methods to check user roles without repeating logic.
 *
 * @package App\Libs\Roles
 */
class RoleHelper
{
    /**
     * Check if the current authenticated user is a super admin.
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function isSuperAdmin(?Authenticatable $user = null): bool
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return false;
            }
        }
        
        if (!$user) {
            return false;
        }

        $superAdminRole = config('filament-shield.super_admin.name', RoleSystemEnum::SUPER_ADMIN->value);
        return method_exists($user, 'hasRole') ? $user->hasRole($superAdminRole) : false;
    }

    /**
     * Check if the current authenticated user is an admin.
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function isAdmin(?Authenticatable $user = null): bool
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return false;
            }
        }
        
        if (!$user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole(RoleSystemEnum::ADMIN->value) : false;
    }

    /**
     * Check if the current authenticated user is a manager.
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function isManager(?Authenticatable $user = null): bool
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return false;
            }
        }
        
        if (!$user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole(RoleSystemEnum::MANAGER->value) : false;
    }

    /**
     * Check if the current authenticated user is a teacher.
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function isTeacher(?Authenticatable $user = null): bool
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return false;
            }
        }
        
        if (!$user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole(RoleSystemEnum::TEACHER->value) : false;
    }

    /**
     * Check if the current authenticated user is a student.
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function isStudent(?Authenticatable $user = null): bool
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return false;
            }
        }
        
        if (!$user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole(RoleSystemEnum::STUDENT->value) : false;
    }

    /**
     * Check if the current authenticated user has any of the specified roles.
     *
     * @param array|string $roles
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function hasAnyRole(array|string $roles, ?Authenticatable $user = null): bool
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return false;
            }
        }
        
        if (!$user) {
            return false;
        }

        return method_exists($user, 'hasAnyRole') ? $user->hasAnyRole($roles) : false;
    }

    /**
     * Check if the current authenticated user has all of the specified roles.
     *
     * @param array|string $roles
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function hasAllRoles(array|string $roles, ?Authenticatable $user = null): bool
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return false;
            }
        }
        
        if (!$user) {
            return false;
        }

        return method_exists($user, 'hasAllRoles') ? $user->hasAllRoles($roles) : false;
    }

    /**
     * Check if the current authenticated user has a specific role.
     *
     * @param string $role
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function hasRole(string $role, ?Authenticatable $user = null): bool
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return false;
            }
        }
        
        if (!$user) {
            return false;
        }

        return method_exists($user, 'hasRole') ? $user->hasRole($role) : false;
    }

    /**
     * Get all roles of the current authenticated user.
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRoles(?Authenticatable $user = null): Collection
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return new Collection();
            }
        }
        
        if (!$user) {
            return new Collection();
        }

        return $user->roles ?? new Collection();
    }

    /**
     * Check if the current authenticated user can modify system roles.
     * Only super admin can modify system roles.
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function canModifySystemRoles(?Authenticatable $user = null): bool
    {
        return static::isSuperAdmin($user);
    }

    /**
     * Check if the current authenticated user has administrative privileges.
     * This includes super admin, admin, and manager roles.
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return bool
     */
    public static function isAdministrative(?Authenticatable $user = null): bool
    {
        return static::hasAnyRole([
            RoleSystemEnum::SUPER_ADMIN->value,
            RoleSystemEnum::ADMIN->value,
            RoleSystemEnum::MANAGER->value,
        ], $user);
    }

    /**
     * Get the highest role of the current authenticated user.
     * Returns the role with the highest priority (super_admin > admin > manager > teacher > student).
     *
     * @param Authenticatable|null $user Optional user instance, defaults to current authenticated user
     * @return string|null
     */
    public static function getHighestRole(?Authenticatable $user = null): ?string
    {
        if ($user === null) {
            try {
                $user = Auth::user();
            } catch (\Exception $e) {
                return null;
            }
        }
        
        if (!$user) {
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
            if (method_exists($user, 'hasRole') ? $user->hasRole($role) : false) {
                return $role;
            }
        }

        return null;
    }

    /**
     * Check if a role is a system role.
     *
     * @param string $roleName
     * @return bool
     */
    public static function isSystemRole(string $roleName): bool
    {
        $systemRoles = [
            RoleSystemEnum::SUPER_ADMIN->value,
            RoleSystemEnum::ADMIN->value,
            RoleSystemEnum::MANAGER->value,
            RoleSystemEnum::TEACHER->value,
            RoleSystemEnum::STUDENT->value,
        ];

        return in_array($roleName, $systemRoles);
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