<?php

namespace App\DTO\User;

use App\DTO\Concerns\InteractsWithArray;
use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;

/**
 * Data Transfer Object for user pagination operations.
 *
 * Handles pagination parameters, filters, and sorting options
 * for paginated user queries.
 */
class UserPaginationData
{
    use InteractsWithArray;

    public function __construct(
        public int $perPage = 15,
        public int $page = 1,
        public ?UserFilterData $filters = null,
        public array $columns = ['*'],
        public array $relations = [],
        public string $sortBy = 'created_at',
        public string $sortDirection = 'desc',
        public bool $withTrashed = false,
        public bool $onlyTrashed = false
    ) {
        // Ensure valid pagination values
        $this->perPage = max(1, min(100, $this->perPage));
        $this->page = max(1, $this->page);
        
        // Initialize filters if not provided
        if ($this->filters === null) {
            $this->filters = new UserFilterData();
        }
    }

    /**
     * Get pagination parameters.
     *
     * @return array{per_page: int, page: int}
     */
    public function getPaginationParams(): array
    {
        return [
            'per_page' => $this->perPage,
            'page' => $this->page
        ];
    }

    /**
     * Get filter criteria for query building.
     *
     * @return array<string, mixed>
     */
    public function getFilterCriteria(): array
    {
        $criteria = [];

        if ($this->filters->status !== null) {
            $criteria['status'] = $this->filters->status->value;
        }

        if ($this->filters->role !== null) {
            $criteria['role'] = $this->filters->role->value;
        }

        return $criteria;
    }

    /**
     * Check if keyword search should be performed.
     */
    public function hasKeywordFilter(): bool
    {
        return $this->filters->keyword !== null && trim($this->filters->keyword) !== '';
    }

    /**
     * Get the search keyword.
     */
    public function getKeyword(): ?string
    {
        return $this->filters->keyword ? trim($this->filters->keyword) : null;
    }

    /**
     * Check if status filter should be applied.
     */
    public function hasStatusFilter(): bool
    {
        return $this->filters->status !== null;
    }

    /**
     * Check if role filter should be applied.
     */
    public function hasRoleFilter(): bool
    {
        return $this->filters->role !== null;
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
            'direction' => strtolower($this->sortDirection) === 'asc' ? 'asc' : 'desc'
        ];
    }

    /**
     * Check if soft deleted records should be included.
     */
    public function shouldIncludeTrashed(): bool
    {
        return $this->withTrashed;
    }

    /**
     * Check if only soft deleted records should be returned.
     */
    public function shouldOnlyTrashed(): bool
    {
        return $this->onlyTrashed;
    }

    /**
     * Get the offset for database queries.
     */
    public function getOffset(): int
    {
        return ($this->page - 1) * $this->perPage;
    }

    /**
     * Get validation rules for pagination parameters.
     *
     * @return array<string, string|array>
     */
    public function getValidationRules(int $maxPerPage = 100, int $maxKeywordLength = 255): array
    {
        return [
            'per_page' => "sometimes|integer|min:1|max:{$maxPerPage}",
            'page' => 'sometimes|integer|min:1',
            'sort_by' => 'sometimes|string|in:id,name,email,status,created_at,updated_at',
            'sort_direction' => 'sometimes|string|in:asc,desc',
            'with_trashed' => 'sometimes|boolean',
            'only_trashed' => 'sometimes|boolean',
            'filters.status' => 'sometimes|in:' . implode(',', array_column(UserStatus::cases(), 'value')),
            'filters.role' => 'sometimes|in:' . implode(',', array_column(RoleSystem::cases(), 'value')),
            'filters.keyword' => "sometimes|string|max:{$maxKeywordLength}",
        ];
    }

    /**
     * Create pagination data from request parameters.
     *
     * @param array<string, mixed> $params
     */
    public static function fromArray(
        array $params, 
        int $defaultPerPage = 15, 
        int $defaultPage = 1, 
        array $defaultColumns = ['*'], 
        array $defaultRelations = [], 
        string $defaultSortBy = 'created_at', 
        string $defaultSortDirection = 'desc'
    ): self {
        $filters = null;
        if (isset($params['filters']) && is_array($params['filters'])) {
            $filterParams = $params['filters'];
            $filters = new UserFilterData(
                status: isset($filterParams['status']) ? UserStatus::from($filterParams['status']) : null,
                keyword: $filterParams['keyword'] ?? null,
                role: isset($filterParams['role']) ? RoleSystem::from($filterParams['role']) : null
            );
        }

        return new self(
            perPage: $params['per_page'] ?? $defaultPerPage,
            page: $params['page'] ?? $defaultPage,
            filters: $filters,
            columns: $params['columns'] ?? $defaultColumns,
            relations: $params['relations'] ?? $defaultRelations,
            sortBy: $params['sort_by'] ?? $defaultSortBy,
            sortDirection: $params['sort_direction'] ?? $defaultSortDirection,
            withTrashed: $params['with_trashed'] ?? false,
            onlyTrashed: $params['only_trashed'] ?? false
        );
    }

    /**
     * Get query parameters for URL generation.
     *
     * @return array<string, mixed>
     */
    public function toQueryParams(): array
    {
        $params = [
            'per_page' => $this->perPage,
            'page' => $this->page,
            'sort_by' => $this->sortBy,
            'sort_direction' => $this->sortDirection,
        ];

        if ($this->withTrashed) {
            $params['with_trashed'] = true;
        }

        if ($this->onlyTrashed) {
            $params['only_trashed'] = true;
        }

        if ($this->filters->status !== null) {
            $params['filters[status]'] = $this->filters->status->value;
        }

        if ($this->filters->role !== null) {
            $params['filters[role]'] = $this->filters->role->value;
        }

        if ($this->filters->keyword !== null) {
            $params['filters[keyword]'] = $this->filters->keyword;
        }

        return $params;
    }

    /**
     * Check if any filters are applied.
     */
    public function hasFilters(): bool
    {
        return $this->filters->status !== null ||
               $this->filters->role !== null ||
               ($this->filters->keyword !== null && trim($this->filters->keyword) !== '');
    }

    /**
     * Get filter summary for display.
     *
     * @return array<string, mixed>
     */
    public function getFilterSummary(): array
    {
        return [
            'status' => $this->filters->status?->value,
            'role' => $this->filters->role?->value,
            'keyword' => $this->filters->keyword,
            'with_trashed' => $this->withTrashed,
            'only_trashed' => $this->onlyTrashed,
        ];
    }
}