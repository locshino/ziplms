<?php

namespace App\Services;

use App\Libs\Roles\RoleHelper;
use App\Models\Course;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class CoursePermissionService
{
    /**
     * Grant manager permission to manage a specific course
     */
    public function grantCoursePermission(User $manager, Course $course): bool
    {
        if (! RoleHelper::isManager($manager)) {
            return false;
        }

        $permissionName = 'manage-course-'.$course->id;

        // Create permission if it doesn't exist
        $permission = Permission::firstOrCreate([
            'name' => $permissionName,
            'guard_name' => 'web',
        ]);

        // Give permission to manager
        $manager->givePermissionTo($permission);

        return true;
    }

    /**
     * Revoke manager permission from a specific course
     */
    public function revokeCoursePermission(User $manager, Course $course): bool
    {
        if (! $manager->hasRole('manager')) {
            return false;
        }

        $permissionName = 'manage-course-'.$course->id;

        if ($manager->hasPermissionTo($permissionName)) {
            $manager->revokePermissionTo($permissionName);
        }

        return true;
    }

    /**
     * Check if manager has permission to manage a course
     */
    public function hasPermissionToManageCourse(User $manager, Course $course): bool
    {
        if (! RoleHelper::isManager($manager)) {
            return false;
        }

        return $manager->can('manage-course-'.$course->id);
    }

    /**
     * Get all courses that a manager can manage
     */
    public function getManagerCourses(User $manager): array
    {
        if (! RoleHelper::isManager($manager)) {
            return [];
        }

        $permissions = $manager->permissions
            ->filter(function ($permission) {
                return str_starts_with($permission->name, 'manage-course-');
            })
            ->pluck('name')
            ->toArray();

        $courseIds = array_map(function ($permission) {
            return str_replace('manage-course-', '', $permission);
        }, $permissions);

        return Course::whereIn('id', $courseIds)->get()->toArray();
    }

    /**
     * Grant multiple course permissions to a manager
     */
    public function grantMultipleCoursePermissions(User $manager, array $courseIds): bool
    {
        if (! RoleHelper::isManager($manager)) {
            return false;
        }

        foreach ($courseIds as $courseId) {
            $course = Course::find($courseId);
            if ($course) {
                $this->grantCoursePermission($manager, $course);
            }
        }

        return true;
    }

    /**
     * Revoke all course permissions from a manager
     */
    public function revokeAllCoursePermissions(User $manager): bool
    {
        if (! $manager->hasRole('manager')) {
            return false;
        }

        $coursePermissions = $manager->permissions
            ->filter(function ($permission) {
                return str_starts_with($permission->name, 'manage-course-');
            });

        foreach ($coursePermissions as $permission) {
            $manager->revokePermissionTo($permission);
        }

        return true;
    }

    /**
     * Sync manager course permissions (revoke all and grant new ones)
     */
    public function syncCoursePermissions(User $manager, array $courseIds): bool
    {
        if (! $manager->hasRole('manager')) {
            return false;
        }

        // Revoke all existing course permissions
        $this->revokeAllCoursePermissions($manager);

        // Grant new permissions
        $this->grantMultipleCoursePermissions($manager, $courseIds);

        return true;
    }
}
