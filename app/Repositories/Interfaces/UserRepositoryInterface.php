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
}
