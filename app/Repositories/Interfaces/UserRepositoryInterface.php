<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface UserRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?Model;

    /**
     * Get users by role.
     */
    public function getUsersByRole(string $role): Collection;

    /**
     * Get active users.
     */
    public function getActiveUsers(): Collection;

    /**
     * Search users by name or email.
     */
    public function searchUsers(string $search): Collection;

    public function getEnrolledCoursesByUserId(string $userId): Collection;

    /**
     * Get users who don't have a specific permission.
     */
    public function getUsersWithoutPermission(string $permission): Collection;

    /**
     * Get users who have a specific permission.
     */
    public function getUsersWithPermission(string $permission): Collection;

    /**
     * Get users not in the given IDs array.
     */
    public function getUsersNotInIds(array $excludedIds): Collection;

    /**
     * Check if user is active (not deleted and status is active).
     */
    public function isActive(string $userId): bool;

    /**
     * Check if user does not exist (deleted or status is inactive or pending).
     */
    public function isNotExist(string $userId): bool;

    /**
     * Check if user is suspended (status is suspended and not deleted).
     */
    public function isSuspended(string $userId): bool;
}
