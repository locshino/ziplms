<?php

namespace App\Repositories\Eloquent;

use App\Exceptions\Repositories\RepositoryException;
use App\Exceptions\Repositories\UserRepositoryException;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * User repository implementation.
 *
 * Handles user data access operations with proper exception handling.
 *
 * @throws RepositoryException When general repository operations fail
 * @throws UserRepositoryException When user-specific operations fail
 */
class UserRepository extends EloquentRepository implements UserRepositoryInterface
{
    /**
     * Get the model class name.
     */
    protected function model(): string
    {
        return User::class;
    }

    /**
     * Find user by email.
     *
     * @throws UserRepositoryException When user with email not found or database error occurs
     */
    public function findByEmail(string $email): ?Model
    {
        try {
            return $this->model->where('email', $email)->first();
        } catch (Exception $e) {
            throw UserRepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get users by role.
     *
     * @throws UserRepositoryException When invalid role provided or database error occurs
     */
    public function getUsersByRole(string $role): Collection
    {
        try {
            return $this->model->role($role)->get();
        } catch (Exception $e) {
            throw UserRepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get active users.
     *
     * @throws RepositoryException When database error occurs
     */
    public function getActiveUsers(): Collection
    {
        try {
            return $this->model->whereNull('deleted_at')->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Search users by name or email.
     *
     * @throws RepositoryException When database error occurs or invalid search parameters
     */
    public function searchUsers(string $search): Collection
    {
        try {
            return $this->model->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })->get();
        } catch (Exception $e) {
            throw RepositoryException::databaseError($e->getMessage());
        }
    }

    public function getEnrolledCoursesByUserId(string $userId): Collection
    {

        try {
            $user = User::with('enrollments')->findOrFail($userId);
            if ($user->hasRole('student')) {
                $enrollments = $user->enrollments;
            } elseif ($user->hasRole('teacher')) {
                $enrollments = $user->taughtCourses;
            }

            return $enrollments;
        } catch (Exception $e) {
            throw UserRepositoryException::databaseError($e->getMessage());
        }
    }
}
