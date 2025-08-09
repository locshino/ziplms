<?php

namespace App\Services\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use App\Models\User;


interface UserServiceInterface extends BaseServiceInterface
{
    /**
     * Create a new user with encrypted password.
     *
     * @param array $payload
     * @return Model
     */
    public function createUser(array $payload): Model;

    /**
     * Update user password.
     *
     * @param string $userId
     * @param string $newPassword
     * @return bool
     */
    public function updatePassword(string $userId, string $newPassword): bool;

    /**
     * Find user by email.
     *
     * @param string $email
     * @return Model|null
     */
    public function findByEmail(string $email): ?Model;

    /**
     * Get users by role.
     *
     * @param string $role
     * @return Collection
     */
    public function getUsersByRole(string $role): Collection;

    /**
     * Get all instructors.
     *
     * @return Collection
     */
    public function getInstructors(): Collection;

    /**
     * Get all students.
     *
     * @return Collection
     */
    public function getStudents(): Collection;

    /**
     * Search users by name or email.
     *
     * @param string $search
     * @return Collection
     */
    public function searchUsers(string $search): Collection;

    /**
     * Get active users only.
     *
     * @return Collection
     */
    public function getActiveUsers(): Collection;

    /**
     * Soft delete a user.
     *
     * @param string $userId
     * @return bool
     */
    public function softDeleteUser(string $userId): bool;

    /**
     * Activate or deactivate user.
     *
     * @param string $userId
     * @param bool $isActive
     * @return bool
     */
    public function toggleUserStatus(string $userId, bool $isActive): bool;

    /**
     * Assign role to user.
     *
     * @param string $userId
     * @param string $role
     * @return bool
     */
    public function assignRole(string $userId, string $role): bool;

    /**
     * Remove role from user.
     *
     * @param string $userId
     * @param string $role
     * @return bool
     */
    public function removeRole(string $userId, string $role): bool;
    /**
     * Get courses that a user has enrolled in.
     *
     * @param string $userId
     * @return Collection
     */

    public function getEnrolledCourses(string $userId): Collection;
    public function updateAvatar(User $user, UploadedFile $file);
    public function updateUserInfo(User $user, array $data, ?UploadedFile $avatarFile = null): User;
}