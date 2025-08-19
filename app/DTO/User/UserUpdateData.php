<?php

namespace App\DTO\User;

use App\DTO\Concerns\InteractsWithArray;
use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use Illuminate\Http\UploadedFile;

/**
 * Data Transfer Object for user update operations.
 *
 * Contains data for updating existing users with proper validation
 * and handling of optional fields.
 */
class UserUpdateData
{
    use InteractsWithArray;

    public function __construct(
        public ?string $name = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?UserStatus $status = null,
        public ?RoleSystem $role = null,
        public ?UploadedFile $avatar = null,
        public ?string $emailVerifiedAt = null,
        public ?string $rememberToken = null,
        public bool $removeAvatar = false
    ) {}

    /**
     * Get data array for model update.
     *
     * Only includes non-null values to avoid overwriting existing data.
     *
     * @return array<string, mixed>
     */
    public function toModelArray(): array
    {
        $data = [];

        if ($this->name !== null) {
            $data['name'] = $this->name;
        }

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->password !== null) {
            $data['password'] = $this->password;
        }

        if ($this->status !== null) {
            $data['status'] = $this->status->value;
        }

        if ($this->emailVerifiedAt !== null) {
            $data['email_verified_at'] = $this->emailVerifiedAt;
        }

        if ($this->rememberToken !== null) {
            $data['remember_token'] = $this->rememberToken;
        }

        return $data;
    }

    /**
     * Get validation rules for user update.
     *
     * @param mixed $userId Current user ID for unique email validation
     * @return array<string, string|array>
     */
    public function getValidationRules(
        mixed $userId = null,
        int $maxNameLength = 255,
        int $maxEmailLength = 255,
        int $minPasswordLength = 8,
        int $maxAvatarSize = 2048,
        string $avatarMimeTypes = 'jpeg,png,jpg,gif'
    ): array {
        $rules = [];

        if ($this->name !== null) {
            $rules['name'] = "required|string|max:{$maxNameLength}";
        }

        if ($this->email !== null) {
            $emailRule = "required|email|max:{$maxEmailLength}";
            if ($userId !== null) {
                $emailRule .= '|unique:users,email,' . $userId;
            } else {
                $emailRule .= '|unique:users,email';
            }
            $rules['email'] = $emailRule;
        }

        if ($this->password !== null) {
            $rules['password'] = "required|string|min:{$minPasswordLength}|confirmed";
        }

        if ($this->status !== null) {
            $rules['status'] = 'required|in:' . implode(',', array_column(UserStatus::cases(), 'value'));
        }

        if ($this->role !== null) {
            $rules['role'] = 'required|in:' . implode(',', array_column(RoleSystem::cases(), 'value'));
        }

        if ($this->avatar !== null) {
            $rules['avatar'] = "sometimes|image|mimes:{$avatarMimeTypes}|max:{$maxAvatarSize}";
        }

        return $rules;
    }

    /**
     * Check if any data is provided for update.
     */
    public function hasData(): bool
    {
        return $this->name !== null ||
               $this->email !== null ||
               $this->password !== null ||
               $this->status !== null ||
               $this->role !== null ||
               $this->avatar !== null ||
               $this->emailVerifiedAt !== null ||
               $this->rememberToken !== null ||
               $this->removeAvatar;
    }

    /**
     * Check if user should be assigned a new role.
     */
    public function shouldUpdateRole(): bool
    {
        return $this->role !== null;
    }

    /**
     * Check if user has new avatar to upload.
     */
    public function hasNewAvatar(): bool
    {
        return $this->avatar !== null;
    }

    /**
     * Check if avatar should be removed.
     */
    public function shouldRemoveAvatar(): bool
    {
        return $this->removeAvatar;
    }

    /**
     * Get the role to assign to the user.
     */
    public function getRoleToAssign(): ?string
    {
        return $this->role?->value;
    }

    /**
     * Check if password is being updated.
     */
    public function isUpdatingPassword(): bool
    {
        return $this->password !== null;
    }

    /**
     * Check if status is being updated.
     */
    public function isUpdatingStatus(): bool
    {
        return $this->status !== null;
    }
}