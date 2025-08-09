<?php

namespace App\Services;

use App\Exceptions\Repositories\RepositoryException;
use App\Exceptions\Services\ServiceException;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\UploadedFile;
use App\Models\User;


/**
 * User service implementation.
 * 
 * Handles user-related business logic operations.
 * 
 * @throws ServiceException When user service operations fail
 * @throws RepositoryException When repository operations fail
 */
class UserService extends BaseService implements UserServiceInterface
{
    /**
     * UserService constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
        parent::__construct($userRepository);
    }

    /**
     * Create a new user with encrypted password.
     *
     * @param array $payload
     * @return Model
     * @throws ServiceException When email already exists or validation fails
     * @throws RepositoryException When user creation fails
     * @throws Exception When transaction fails
     */
    public function createUser(array $payload): Model
    {
        try {
            // Validate email uniqueness
            if ($this->userRepository->findByEmail($payload['email'])) {
                throw new Exception('Email already exists.');
            }

            // Hash password if provided
            if (isset($payload['password'])) {
                $payload['password'] = Hash::make($payload['password']);
            }

            return DB::transaction(function () use ($payload) {
                $user = $this->userRepository->create($payload);

                // Assign default role if specified
                if (isset($payload['role'])) {
                    $user->assignRole($payload['role']);
                }

                return $user;
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (ServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Update user password.
     *
     * @param string $userId
     * @param string $newPassword
     * @return bool
     * @throws RepositoryException When password update fails or user not found
     */
    public function updatePassword(string $userId, string $newPassword): bool
    {
        try {
            return DB::transaction(function () use ($userId, $newPassword) {
                return $this->userRepository->updateById($userId, [
                    'password' => Hash::make($newPassword)
                ]);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to update password: ' . $e->getMessage());
        }
    }

    /**
     * Find user by email.
     *
     * @param string $email
     * @return Model|null
     * @throws RepositoryException When database error occurs
     */
    public function findByEmail(string $email): ?Model
    {
        try {
            return $this->userRepository->findByEmail($email);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to find user by email: ' . $e->getMessage());
        }
    }

    /**
     * Get users by role.
     *
     * @param string $role
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getUsersByRole(string $role): Collection
    {
        try {
            return $this->userRepository->getUsersByRole($role);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get users by role: ' . $e->getMessage());
        }
    }

    /**
     * Get all instructors.
     *
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getInstructors(): Collection
    {
        try {
            return $this->getUsersByRole('instructor');
        } catch (RepositoryException $e) {
            throw $e;
        } catch (ServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get instructors: ' . $e->getMessage());
        }
    }

    /**
     * Get all students.
     *
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getStudents(): Collection
    {
        try {
            return $this->getUsersByRole('student');
        } catch (RepositoryException $e) {
            throw $e;
        } catch (ServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get students: ' . $e->getMessage());
        }
    }

    /**
     * Search users by name or email.
     *
     * @param string $search
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function searchUsers(string $search): Collection
    {
        try {
            return $this->userRepository->searchUsers($search);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to search users: ' . $e->getMessage());
        }
    }

    /**
     * Get active users only.
     *
     * @return Collection
     * @throws RepositoryException When database error occurs
     */
    public function getActiveUsers(): Collection
    {
        try {
            return $this->userRepository->getActiveUsers();
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to get active users: ' . $e->getMessage());
        }
    }

    /**
     * Soft delete a user.
     *
     * @param string $userId
     * @return bool
     * @throws RepositoryException When deletion fails or user not found
     */
    public function softDeleteUser(string $userId): bool
    {
        try {
            return $this->userRepository->deleteById($userId);
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to soft delete user: ' . $e->getMessage());
        }
    }

    /**
     * Activate or deactivate user.
     *
     * @param string $userId
     * @param bool $isActive
     * @return bool
     * @throws RepositoryException When status update fails or user not found
     */
    public function toggleUserStatus(string $userId, bool $isActive): bool
    {
        try {
            return DB::transaction(function () use ($userId, $isActive) {
                $payload = $isActive ? ['deleted_at' => null] : [];

                if (!$isActive) {
                    return $this->userRepository->deleteById($userId);
                }

                return $this->userRepository->updateById($userId, $payload);
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (ServiceException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to toggle user status: ' . $e->getMessage());
        }
    }

    /**
     * Assign role to user.
     *
     * @param string $userId
     * @param string $role
     * @return bool
     * @throws RepositoryException When user lookup fails
     * @throws ServiceException When role assignment fails
     */
    public function assignRole(string $userId, string $role): bool
    {
        try {
            return DB::transaction(function () use ($userId, $role) {
                $user = $this->userRepository->findById($userId);

                if (!$user) {
                    return false;
                }

                $user->assignRole($role);
                return true;
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to assign role: ' . $e->getMessage());
        }
    }

    /**
     * Remove role from user.
     *
     * @param string $userId
     * @param string $role
     * @return bool
     * @throws RepositoryException When user lookup fails
     * @throws ServiceException When role removal fails
     */
    public function removeRole(string $userId, string $role): bool
    {
        try {
            return DB::transaction(function () use ($userId, $role) {
                $user = $this->userRepository->findById($userId);

                if (!$user) {
                    return false;
                }

                $user->removeRole($role);
                return true;
            });
        } catch (RepositoryException $e) {
            throw $e;
        } catch (Exception $e) {
            throw ServiceException::operationFailed('Failed to remove role: ' . $e->getMessage());
        }
    }

    public function getEnrolledCourses(string $userId): Collection
    {
        return $this->userRepository->getEnrolledCoursesByUserId($userId);
    }
    public function updateAvatar(User $user, UploadedFile $file)
    {
        $user->clearMediaCollection('avatars');

        $user->addMedia($file)->toMediaCollection('avatars');
    }
    public function updateUserInfo(User $user, array $data, ?UploadedFile $avatarFile = null): User
    {

        $user->fill($data);
        $user->save();


        if ($avatarFile) {

            $user->clearMediaCollection('avatars');

            $user->addMedia($avatarFile)->toMediaCollection('avatars');
        }

        return $user;
    }
}