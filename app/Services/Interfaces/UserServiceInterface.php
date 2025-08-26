<?php

namespace App\Services\Interfaces;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

interface UserServiceInterface extends BaseServiceInterface
{
    /**
     * Create a new user with encrypted password.
     */
    public function createUser(array $payload): Model;

    /**
     * Update user password.
     */
    public function updatePassword(string $userId, string $newPassword): bool;

    /**
     * Find user by email.
     */
    public function findByEmail(string $email): ?Model;

    /**
     * Get users by role.
     */
    public function getUsersByRole(string $role): Collection;

    /**
     * Get all teachers.
     */
    public function getTeachers(): Collection;

    /**
     * Get all students.
     */
    public function getStudents(): Collection;

    /**
     * Search users by name or email.
     */
    public function searchUsers(string $search): Collection;

    /**
     * Get active users only.
     */
    public function getActiveUsers(): Collection;

    /**
     * Soft delete a user.
     */
    public function softDeleteUser(string $userId): bool;

    /**
     * Activate or deactivate user.
     */
    public function toggleUserStatus(string $userId, bool $isActive): bool;

    /**
     * Assign role to user.
     */
    public function assignRole(string $userId, string $role): bool;

    /**
     * Remove role from user.
     */
    public function removeRole(string $userId, string $role): bool;

    /**
     * Get courses that a user has enrolled in.
     */
    public function getEnrolledCourses(string $userId): Collection;

    public function updateAvatar(User $user, UploadedFile $file);

    public function updateUserInfo(User $user, array $data, ?UploadedFile $avatarFile = null): User;

    /**
     * Check if a user is active.
     */
    public function checkActive(string $userId): bool;

    /**
     * Check if a user does not exist.
     */
    public function checkNotExist(string $userId): bool;

    /**
     * Check if a user is suspended.
     */
    public function checkSuspended(string $userId): bool;
}
