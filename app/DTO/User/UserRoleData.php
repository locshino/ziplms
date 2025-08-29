<?php

namespace App\DTO\User;

use App\DTO\Concerns\InteractsWithArray;
use App\Enums\System\RoleSystem;
use Carbon\Carbon;

/**
 * Data Transfer Object for user role and permission management.
 *
 * Handles role assignments, permission checks, and role-based
 * operations for users in the system.
 */
class UserRoleData
{
    use InteractsWithArray;

    public function __construct(
        public mixed $userId,
        public ?RoleSystem $role = null,
        public array $roles = [],
        public array $permissions = [],
        public array $directPermissions = [],
        public mixed $courseId = null,
        public mixed $assignedBy = null,
        public ?Carbon $assignedAt = null,
        public ?Carbon $expiresAt = null,
        public bool $isTemporary = false,
        public ?string $reason = null,
        public array $metadata = []
    ) {}

    /**
     * Check if user has a specific role.
     */
    public function hasRole(RoleSystem $role): bool
    {
        if ($this->role === $role) {
            return true;
        }

        return in_array($role->value, array_map(fn ($r) => is_string($r) ? $r : $r->value, $this->roles));
    }

    /**
     * Check if user has any of the specified roles.
     *
     * @param  array<RoleSystem>  $roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the specified roles.
     *
     * @param  array<RoleSystem>  $roles
     */
    public function hasAllRoles(array $roles): bool
    {
        foreach ($roles as $role) {
            if (! $this->hasRole($role)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions) ||
               in_array($permission, $this->directPermissions);
    }

    /**
     * Check if user has any of the specified permissions.
     *
     * @param  array<string>  $permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the specified permissions.
     *
     * @param  array<string>  $permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! $this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user is a super admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole(RoleSystem::SUPER_ADMIN);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(RoleSystem::ADMIN);
    }

    /**
     * Check if user is a manager.
     */
    public function isManager(): bool
    {
        return $this->hasRole(RoleSystem::MANAGER);
    }

    /**
     * Check if user is a teacher.
     */
    public function isTeacher(): bool
    {
        return $this->hasRole(RoleSystem::TEACHER);
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->hasRole(RoleSystem::STUDENT);
    }

    /**
     * Check if user can bypass permissions (Super Admin).
     */
    public function canBypassPermissions(): bool
    {
        return $this->isSuperAdmin();
    }

    /**
     * Check if role assignment is temporary.
     */
    public function isTemporaryAssignment(): bool
    {
        return $this->isTemporary;
    }

    /**
     * Check if role assignment has expired.
     */
    public function hasExpired(): bool
    {
        return $this->expiresAt !== null && $this->expiresAt->isPast();
    }

    /**
     * Check if role assignment is still valid.
     */
    public function isValid(): bool
    {
        return ! $this->hasExpired();
    }

    /**
     * Get the highest priority role.
     */
    public function getHighestRole(): ?RoleSystem
    {
        $rolePriority = [
            RoleSystem::SUPER_ADMIN->value => 5,
            RoleSystem::ADMIN->value => 4,
            RoleSystem::MANAGER->value => 3,
            RoleSystem::TEACHER->value => 2,
            RoleSystem::STUDENT->value => 1,
        ];

        $highestPriority = 0;
        $highestRole = null;

        foreach ($this->roles as $role) {
            $roleValue = is_string($role) ? $role : $role->value;
            $priority = $rolePriority[$roleValue] ?? 0;

            if ($priority > $highestPriority) {
                $highestPriority = $priority;
                $highestRole = RoleSystem::from($roleValue);
            }
        }

        return $highestRole ?? $this->role;
    }

    /**
     * Get all role names as strings.
     *
     * @return array<string>
     */
    public function getRoleNames(): array
    {
        return array_map(fn ($role) => is_string($role) ? $role : $role->value, $this->roles);
    }

    /**
     * Get all permission names.
     *
     * @return array<string>
     */
    public function getAllPermissions(): array
    {
        return array_unique(array_merge($this->permissions, $this->directPermissions));
    }

    /**
     * Get role assignment data for database operations.
     *
     * @return array<string, mixed>
     */
    public function toRoleAssignmentArray(): array
    {
        $data = [
            'user_id' => $this->userId,
        ];

        if ($this->role !== null) {
            $data['role'] = $this->role->value;
        }

        if ($this->courseId !== null) {
            $data['course_id'] = $this->courseId;
        }

        if ($this->assignedBy !== null) {
            $data['assigned_by'] = $this->assignedBy;
        }

        if ($this->assignedAt !== null) {
            $data['assigned_at'] = $this->assignedAt;
        }

        if ($this->expiresAt !== null) {
            $data['expires_at'] = $this->expiresAt;
        }

        if ($this->reason !== null) {
            $data['reason'] = $this->reason;
        }

        if (! empty($this->metadata)) {
            $data['metadata'] = $this->metadata;
        }

        return $data;
    }

    /**
     * Get role summary for display.
     *
     * @return array<string, mixed>
     */
    public function getRoleSummary(): array
    {
        return [
            'user_id' => $this->userId,
            'primary_role' => $this->role?->value,
            'highest_role' => $this->getHighestRole()?->value,
            'all_roles' => $this->getRoleNames(),
            'permissions_count' => count($this->getAllPermissions()),
            'is_super_admin' => $this->isSuperAdmin(),
            'is_admin' => $this->isAdmin(),
            'is_manager' => $this->isManager(),
            'is_teacher' => $this->isTeacher(),
            'is_student' => $this->isStudent(),
            'can_bypass_permissions' => $this->canBypassPermissions(),
            'is_temporary' => $this->isTemporary,
            'has_expired' => $this->hasExpired(),
            'is_valid' => $this->isValid(),
            'assigned_at' => $this->assignedAt?->toISOString(),
            'expires_at' => $this->expiresAt?->toISOString(),
        ];
    }

    /**
     * Get validation rules for role assignment.
     *
     * @return array<string, string|array>
     */
    public function getValidationRules(int $maxPermissionLength = 255, int $maxReasonLength = 500): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'role' => 'sometimes|in:'.implode(',', array_column(RoleSystem::cases(), 'value')),
            'roles' => 'sometimes|array',
            'roles.*' => 'in:'.implode(',', array_column(RoleSystem::cases(), 'value')),
            'permissions' => 'sometimes|array',
            'permissions.*' => "string|max:{$maxPermissionLength}",
            'course_id' => 'sometimes|exists:courses,id',
            'assigned_by' => 'sometimes|exists:users,id',
            'assigned_at' => 'sometimes|date',
            'expires_at' => 'sometimes|date|after:assigned_at',
            'is_temporary' => 'sometimes|boolean',
            'reason' => "sometimes|string|max:{$maxReasonLength}",
            'metadata' => 'sometimes|array',
        ];
    }

    /**
     * Create role data from user model.
     *
     * @param  mixed  $user  User model instance
     */
    public static function fromUser($user): self
    {
        $roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames() : [];
        $permissions = method_exists($user, 'getAllPermissions') ?
                      $user->getAllPermissions()->pluck('name')->toArray() : [];
        $directPermissions = method_exists($user, 'getDirectPermissions') ?
                            $user->getDirectPermissions()->pluck('name')->toArray() : [];

        return new self(
            userId: $user->id,
            roles: $roles,
            permissions: $permissions,
            directPermissions: $directPermissions
        );
    }

    /**
     * Check if user has course-specific role.
     */
    public function hasCourseRole(): bool
    {
        return $this->courseId !== null;
    }

    /**
     * Get course-specific permissions.
     *
     * @return array<string>
     */
    public function getCoursePermissions(): array
    {
        if (! $this->hasCourseRole()) {
            return [];
        }

        // Filter permissions that are course-specific
        return array_filter($this->getAllPermissions(), function ($permission) {
            return str_contains($permission, 'course') ||
                   str_contains($permission, 'lesson') ||
                   str_contains($permission, 'assignment') ||
                   str_contains($permission, 'quiz');
        });
    }
}
