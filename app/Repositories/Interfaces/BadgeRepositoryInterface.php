<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

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
     * @param  array  $relations  Relations to eager load
     *
     * @throws \App\Exceptions\Repositories\BadgeRepositoryException
     */
    public function findBadgeByIdOrFail(string $id, array $relations = []): \App\Models\Badge;

    /**
     * Update badge by ID.
     *
     * @throws \App\Exceptions\Repositories\BadgeRepositoryException
     */
    public function updateBadgeById(string $id, array $data): \App\Models\Badge;

    /**
     * Delete badge by ID.
     *
     * @throws \App\Exceptions\Repositories\BadgeRepositoryException
     */
    public function deleteBadgeById(string $id): bool;

    /**
     * Get badges by status.
     *
     * @param  string  $status  The badge status
     */
    public function getBadgesByStatus(string $status): Collection;

    /**
     * Get badges by category.
     *
     * @param  string  $category  The badge category
     */
    public function getBadgesByCategory(string $category): Collection;

    /**
     * Get badges by type.
     *
     * @param  string  $type  The badge type
     */
    public function getBadgesByType(string $type): Collection;

    /**
     * Get badge with conditions.
     *
     * @param  string  $badgeId  The badge ID
     */
    public function getBadgeWithConditions(string $badgeId): mixed;

    /**
     * Get badge with user achievements.
     *
     * @param  string  $badgeId  The badge ID
     */
    public function getBadgeWithUserAchievements(string $badgeId): mixed;

    /**
     * Get conditions by badge.
     *
     * @param  string  $badgeId  The badge ID
     */
    public function getConditionsByBadge(string $badgeId): Collection;

    /**
     * Get user achievements by badge.
     *
     * @param  string  $badgeId  The badge ID
     */
    public function getUserAchievementsByBadge(string $badgeId): Collection;

    /**
     * Get achievements count for badge.
     *
     * @param  string  $badgeId  The badge ID
     */
    public function getAchievementsCount(string $badgeId): int;

    /**
     * Get badge achievement statistics.
     *
     * @param  string  $badgeId  The badge ID
     */
    public function getBadgeAchievementStats(string $badgeId): array;

    /**
     * Get badge progress distribution.
     *
     * @param  string  $badgeId  The badge ID
     */
    public function getBadgeProgressDistribution(string $badgeId): array;

    /**
     * Get average completion time for badge.
     *
     * @param  string  $badgeId  The badge ID
     */
    public function getAverageCompletionTime(string $badgeId): ?float;

    /**
     * Get badges by IDs.
     *
     * @param  array  $badgeIds  Array of badge IDs
     */
    public function getBadgesByIds(array $badgeIds): Collection;

    /**
     * Search badges by keyword.
     *
     * @param  string  $keyword  The search keyword
     * @param  array  $filters  Additional filters
     */
    public function searchBadges(string $keyword, array $filters = []): Collection;

    /**
     * Get paginated badges.
     *
     * @param  int  $perPage  Items per page
     * @param  array  $filters  Additional filters
     */
    public function getPaginatedBadges(int $perPage = 15, array $filters = []): LengthAwarePaginator;

    /**
     * Get featured badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     */
    public function getFeaturedBadges(int $limit = 10): Collection;

    /**
     * Get popular badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     */
    public function getPopularBadges(int $limit = 10): Collection;

    /**
     * Get recent badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     */
    public function getRecentBadges(int $limit = 10): Collection;

    /**
     * Get badges by status and category.
     *
     * @param  string  $status  The badge status
     * @param  string  $category  The badge category
     */
    public function getBadgesByStatusAndCategory(string $status, string $category): Collection;

    /**
     * Get badges with minimum achievements.
     *
     * @param  int  $minAchievements  Minimum number of achievements
     */
    public function getBadgesWithMinAchievements(int $minAchievements): Collection;

    /**
     * Get badges by difficulty level.
     *
     * @param  string  $difficulty  The difficulty level
     */
    public function getBadgesByDifficulty(string $difficulty): Collection;

    /**
     * Get badges by points range.
     *
     * @param  int  $minPoints  Minimum points
     * @param  int  $maxPoints  Maximum points
     */
    public function getBadgesByPointsRange(int $minPoints, int $maxPoints): Collection;

    /**
     * Get user badges.
     *
     * @param  string  $userId  The user ID
     */
    public function getUserBadges(string $userId): Collection;

    /**
     * Get user badge progress.
     *
     * @param  string  $userId  The user ID
     * @param  string  $badgeId  The badge ID
     */
    public function getUserBadgeProgress(string $userId, string $badgeId): array;

    /**
     * Check if user has badge.
     *
     * @param  string  $userId  The user ID
     * @param  string  $badgeId  The badge ID
     */
    public function userHasBadge(string $userId, string $badgeId): bool;

    /**
     * Get eligible badges for user.
     *
     * @param  string  $userId  The user ID
     */
    public function getEligibleBadgesForUser(string $userId): Collection;

    /**
     * Get badge leaderboard.
     *
     * @param  string  $badgeId  The badge ID
     * @param  int  $limit  Maximum number of users to return
     */
    public function getBadgeLeaderboard(string $badgeId, int $limit = 10): Collection;
}
