<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection as SupportCollection;

/**
 * Interface for badge repository operations.
 *
 * This interface defines the contract for badge data access operations
 * in the LMS system, including badge management, conditions tracking,
 * and achievement statistics.
 *
 * @extends EloquentRepositoryInterface
 */
interface BadgeRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Find badge by ID or fail.
     *
     * @param  string  $id
     * @param  array  $relations  Relations to eager load
     * @return \App\Models\Badge
     * @throws \App\Exceptions\Repositories\BadgeRepositoryException
     */
    public function findBadgeByIdOrFail(string $id, array $relations = []): \App\Models\Badge;

    /**
     * Update badge by ID.
     *
     * @param  string  $id
     * @param  array  $data
     * @return \App\Models\Badge
     * @throws \App\Exceptions\Repositories\BadgeRepositoryException
     */
    public function updateBadgeById(string $id, array $data): \App\Models\Badge;

    /**
     * Delete badge by ID.
     *
     * @param  string  $id
     * @return bool
     * @throws \App\Exceptions\Repositories\BadgeRepositoryException
     */
    public function deleteBadgeById(string $id): bool;
    /**
     * Get badges by status.
     *
     * @param  string  $status  The badge status
     * @return Collection
     */
    public function getBadgesByStatus(string $status): Collection;

    /**
     * Get badges by category.
     *
     * @param  string  $category  The badge category
     * @return Collection
     */
    public function getBadgesByCategory(string $category): Collection;

    /**
     * Get badges by type.
     *
     * @param  string  $type  The badge type
     * @return Collection
     */
    public function getBadgesByType(string $type): Collection;

    /**
     * Get badge with conditions.
     *
     * @param  string  $badgeId  The badge ID
     * @return mixed
     */
    public function getBadgeWithConditions(string $badgeId): mixed;

    /**
     * Get badge with user achievements.
     *
     * @param  string  $badgeId  The badge ID
     * @return mixed
     */
    public function getBadgeWithUserAchievements(string $badgeId): mixed;

    /**
     * Get conditions by badge.
     *
     * @param  string  $badgeId  The badge ID
     * @return Collection
     */
    public function getConditionsByBadge(string $badgeId): Collection;

    /**
     * Get user achievements by badge.
     *
     * @param  string  $badgeId  The badge ID
     * @return Collection
     */
    public function getUserAchievementsByBadge(string $badgeId): Collection;

    /**
     * Get achievements count for badge.
     *
     * @param  string  $badgeId  The badge ID
     * @return int
     */
    public function getAchievementsCount(string $badgeId): int;

    /**
     * Get badge achievement statistics.
     *
     * @param  string  $badgeId  The badge ID
     * @return array
     */
    public function getBadgeAchievementStats(string $badgeId): array;

    /**
     * Get badge progress distribution.
     *
     * @param  string  $badgeId  The badge ID
     * @return array
     */
    public function getBadgeProgressDistribution(string $badgeId): array;

    /**
     * Get average completion time for badge.
     *
     * @param  string  $badgeId  The badge ID
     * @return float|null
     */
    public function getAverageCompletionTime(string $badgeId): ?float;

    /**
     * Get badges by IDs.
     *
     * @param  array  $badgeIds  Array of badge IDs
     * @return Collection
     */
    public function getBadgesByIds(array $badgeIds): Collection;

    /**
     * Search badges by keyword.
     *
     * @param  string  $keyword  The search keyword
     * @param  array  $filters  Additional filters
     * @return Collection
     */
    public function searchBadges(string $keyword, array $filters = []): Collection;

    /**
     * Get paginated badges.
     *
     * @param  int  $perPage  Items per page
     * @param  array  $filters  Additional filters
     * @return LengthAwarePaginator
     */
    public function getPaginatedBadges(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    /**
     * Get featured badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     * @return Collection
     */
    public function getFeaturedBadges(int $limit = 10): Collection;

    /**
     * Get popular badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     * @return Collection
     */
    public function getPopularBadges(int $limit = 10): Collection;

    /**
     * Get recent badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     * @return Collection
     */
    public function getRecentBadges(int $limit = 10): Collection;

    /**
     * Get badges by status and category.
     *
     * @param  string  $status  The badge status
     * @param  string  $category  The badge category
     * @return Collection
     */
    public function getBadgesByStatusAndCategory(string $status, string $category): Collection;

    /**
     * Get badges with minimum achievements.
     *
     * @param  int  $minAchievements  Minimum number of achievements
     * @return Collection
     */
    public function getBadgesWithMinAchievements(int $minAchievements): Collection;

    /**
     * Get badges by difficulty level.
     *
     * @param  string  $difficulty  The difficulty level
     * @return Collection
     */
    public function getBadgesByDifficulty(string $difficulty): Collection;

    /**
     * Get badges by points range.
     *
     * @param  int  $minPoints  Minimum points
     * @param  int  $maxPoints  Maximum points
     * @return Collection
     */
    public function getBadgesByPointsRange(int $minPoints, int $maxPoints): Collection;

    /**
     * Get user badges.
     *
     * @param  string  $userId  The user ID
     * @return Collection
     */
    public function getUserBadges(string $userId): Collection;

    /**
     * Get user badge progress.
     *
     * @param  string  $userId  The user ID
     * @param  string  $badgeId  The badge ID
     * @return array
     */
    public function getUserBadgeProgress(string $userId, string $badgeId): array;

    /**
     * Check if user has badge.
     *
     * @param  string  $userId  The user ID
     * @param  string  $badgeId  The badge ID
     * @return bool
     */
    public function userHasBadge(string $userId, string $badgeId): bool;

    /**
     * Get eligible badges for user.
     *
     * @param  string  $userId  The user ID
     * @return Collection
     */
    public function getEligibleBadgesForUser(string $userId): Collection;

    /**
     * Get badge leaderboard.
     *
     * @param  string  $badgeId  The badge ID
     * @param  int  $limit  Maximum number of users to return
     * @return Collection
     */
    public function getBadgeLeaderboard(string $badgeId, int $limit = 10): Collection;
}