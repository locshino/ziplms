<?php

namespace App\DTO\User;

use App\DTO\Concerns\InteractsWithArray;
use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use Carbon\Carbon;

/**
 * Data Transfer Object for user search operations.
 *
 * Provides comprehensive search criteria for finding users
 * with various filters and sorting options.
 */
class UserSearchData
{
    use InteractsWithArray;

    public function __construct(
        public ?string $keyword = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?UserStatus $status = null,
        public ?RoleSystem $role = null,
        public ?Carbon $createdAfter = null,
        public ?Carbon $createdBefore = null,
        public ?Carbon $lastLoginAfter = null,
        public ?Carbon $lastLoginBefore = null,
        public ?bool $emailVerified = null,
        public ?bool $hasAvatar = null,
        public array $excludeIds = [],
        public array $includeIds = [],
        public array $columns = ['*'],
        public array $relations = [],
        public string $sortBy = 'created_at',
        public string $sortDirection = 'desc',
        public ?int $limit = null
    ) {}

    /**
     * Get search criteria for query building.
     *
     * @return array<string, mixed>
     */
    public function getSearchCriteria(): array
    {
        $criteria = [];

        if ($this->name !== null) {
            $criteria['name'] = ['like', "%{$this->name}%"];
        }

        if ($this->email !== null) {
            $criteria['email'] = ['like', "%{$this->email}%"];
        }

        if ($this->status !== null) {
            $criteria['status'] = $this->status->value;
        }

        if ($this->createdAfter !== null) {
            $criteria['created_at'] = ['>=', $this->createdAfter];
        }

        if ($this->createdBefore !== null) {
            $criteria['created_at'] = ['<=', $this->createdBefore];
        }

        if ($this->emailVerified !== null) {
            if ($this->emailVerified) {
                $criteria['email_verified_at'] = ['not_null'];
            } else {
                $criteria['email_verified_at'] = ['null'];
            }
        }

        return $criteria;
    }

    /**
     * Check if keyword search should be performed.
     */
    public function hasKeywordSearch(): bool
    {
        return $this->keyword !== null && trim($this->keyword) !== '';
    }

    /**
     * Check if role filter should be applied.
     */
    public function hasRoleFilter(): bool
    {
        return $this->role !== null;
    }

    /**
     * Check if date range filter should be applied.
     */
    public function hasDateRangeFilter(): bool
    {
        return $this->createdAfter !== null || $this->createdBefore !== null;
    }

    /**
     * Check if last login filter should be applied.
     */
    public function hasLastLoginFilter(): bool
    {
        return $this->lastLoginAfter !== null || $this->lastLoginBefore !== null;
    }

    /**
     * Check if ID exclusion should be applied.
     */
    public function hasExcludeIds(): bool
    {
        return ! empty($this->excludeIds);
    }

    /**
     * Check if ID inclusion should be applied.
     */
    public function hasIncludeIds(): bool
    {
        return ! empty($this->includeIds);
    }

    /**
     * Get the keyword for search.
     */
    public function getKeyword(): ?string
    {
        return $this->keyword ? trim($this->keyword) : null;
    }

    /**
     * Get the role value for filtering.
     */
    public function getRoleValue(): ?string
    {
        return $this->role?->value;
    }

    /**
     * Get columns to select.
     *
     * @return array<string>
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * Get relations to load.
     *
     * @return array<string>
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * Get sorting configuration.
     *
     * @return array{column: string, direction: string}
     */
    public function getSorting(): array
    {
        return [
            'column' => $this->sortBy,
            'direction' => strtolower($this->sortDirection) === 'asc' ? 'asc' : 'desc',
        ];
    }

    /**
     * Check if result limit should be applied.
     */
    public function hasLimit(): bool
    {
        return $this->limit !== null && $this->limit > 0;
    }

    /**
     * Get the result limit.
     */
    public function getLimit(): ?int
    {
        return $this->limit;
    }

    /**
     * Get validation rules for search parameters.
     *
     * @return array<string, string|array>
     */
    public function getValidationRules(int $maxFieldLength = 255, int $maxLimit = 1000): array
    {
        return [
            'keyword' => "sometimes|string|max:{$maxFieldLength}",
            'name' => "sometimes|string|max:{$maxFieldLength}",
            'email' => "sometimes|email|max:{$maxFieldLength}",
            'status' => 'sometimes|in:'.implode(',', array_column(UserStatus::cases(), 'value')),
            'role' => 'sometimes|in:'.implode(',', array_column(RoleSystem::cases(), 'value')),
            'created_after' => 'sometimes|date',
            'created_before' => 'sometimes|date|after_or_equal:created_after',
            'last_login_after' => 'sometimes|date',
            'last_login_before' => 'sometimes|date|after_or_equal:last_login_after',
            'email_verified' => 'sometimes|boolean',
            'has_avatar' => 'sometimes|boolean',
            'exclude_ids' => 'sometimes|array',
            'exclude_ids.*' => 'integer|exists:users,id',
            'include_ids' => 'sometimes|array',
            'include_ids.*' => 'integer|exists:users,id',
            'sort_by' => 'sometimes|string|in:id,name,email,status,created_at,updated_at',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'limit' => "sometimes|integer|min:1|max:{$maxLimit}",
        ];
    }
}
