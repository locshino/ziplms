<?php

namespace App\DTO\User;

use App\DTO\Concerns\InteractsWithArray;
use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use Illuminate\Http\UploadedFile;

/**
 * Data Transfer Object for user creation operations.
 *
 * Contains all necessary data for creating a new user in the system,
 * including validation rules and default values.
 */
class UserCreateData
{
    use InteractsWithArray;

    public function __construct(
        public string $name,
        public string $email,
        public string $password,
        public ?UserStatus $status = null,
        public ?RoleSystem $role = null,
        public ?UploadedFile $avatar = null,
        public ?string $emailVerifiedAt = null,
        public ?string $rememberToken = null
    ) {
        // Set default status if not provided
        $this->status = $status ?? UserStatus::ACTIVE;

        // Set default role if not provided
        $this->role = $role ?? RoleSystem::STUDENT;
    }

    /**
     * Get data array for model creation.
     *
     * @return array<string, mixed>
     */
    public function toModelArray(): array
    {
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'status' => $this->status->value,
        ];

        if ($this->emailVerifiedAt !== null) {
            $data['email_verified_at'] = $this->emailVerifiedAt;
        }

        if ($this->rememberToken !== null) {
            $data['remember_token'] = $this->rememberToken;
        }

        return $data;
    }

    /**
     * Get validation rules for user creation.
     *
     * @return array<string, string|array>
     */
    public function getValidationRules(
        int $maxNameLength = 255,
        int $maxEmailLength = 255,
        int $minPasswordLength = 8,
        int $maxAvatarSize = 2048,
        string $avatarMimeTypes = 'jpeg,png,jpg,gif'
    ): array {
        return [
            'name' => "required|string|max:{$maxNameLength}",
            'email' => "required|email|unique:users,email|max:{$maxEmailLength}",
            'password' => "required|string|min:{$minPasswordLength}|confirmed",
            'status' => 'sometimes|in:'.implode(',', array_column(UserStatus::cases(), 'value')),
            'role' => 'sometimes|in:'.implode(',', array_column(RoleSystem::cases(), 'value')),
            'avatar' => "sometimes|image|mimes:{$avatarMimeTypes}|max:{$maxAvatarSize}",
        ];
    }

    /**
     * Check if user should be assigned a role after creation.
     */
    public function shouldAssignRole(): bool
    {
        return $this->role !== null;
    }

    /**
     * Check if user has avatar to upload.
     */
    public function hasAvatar(): bool
    {
        return $this->avatar !== null;
    }

    /**
     * Get the role to assign to the user.
     */
    public function getRoleToAssign(): ?string
    {
        return $this->role?->value;
    }
}
