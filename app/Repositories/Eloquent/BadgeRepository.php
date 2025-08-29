<?php

namespace App\Repositories\Eloquent;

use App\Enums\Status\BadgeStatus;
use App\Exceptions\Repositories\BadgeRepositoryException;
use App\Models\Badge;
use App\Models\User;
use App\Repositories\Interfaces\BadgeRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;

/**
 * Badge repository implementation.
 *
 * This repository handles badge data access operations in the LMS system,
 * including badge management, conditions tracking, and achievement statistics.
 */
class BadgeRepository extends EloquentRepository implements BadgeRepositoryInterface
{
    /**
     * Specify Model class name.
     */
    public function model(): string
    {
        return Badge::class;
    }

    /**
     * Find badge by ID or fail.
     *
     * @throws BadgeRepositoryException
     */
    public function findBadgeByIdOrFail(string $id, array $relations = []): Badge
    {
        try {
            return $this->model->with($relations)->findOrFail($id);
        } catch (ModelNotFoundException $e) {
            throw BadgeRepositoryException::notFound($id);
        } catch (QueryException $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        } catch (\Exception $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Update badge by ID.
     *
     * @throws BadgeRepositoryException
     */
    public function updateBadgeById(string $id, array $data): Badge
    {
        try {
            $badge = $this->findBadgeByIdOrFail($id);
            $badge->update($data);

            return $badge->fresh();
        } catch (BadgeRepositoryException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        } catch (ValidationException $e) {
            throw BadgeRepositoryException::validationFailed($e->getMessage());
        } catch (\Exception $e) {
            throw BadgeRepositoryException::updateFailed($id, $e->getMessage());
        }
    }

    /**
     * Delete badge by ID.
     *
     * @throws BadgeRepositoryException
     */
    public function deleteBadgeById(string $id): bool
    {
        try {
            $badge = $this->findBadgeByIdOrFail($id);

            return $badge->delete();
        } catch (BadgeRepositoryException $e) {
            throw $e;
        } catch (QueryException $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        } catch (\Exception $e) {
            throw BadgeRepositoryException::deleteFailed($id, $e->getMessage());
        }
    }

    /**
     * Get badges by status.
     *
     * @param  string  $status  The badge status
     */
    public function getBadgesByStatus(string $status): Collection
    {
        return $this->model->where('status', $status)->get();
    }

    /**
     * Get badges by category.
     *
     * @param  string  $category  The badge category
     */
    public function getBadgesByCategory(string $category): Collection
    {
        return $this->model->whereHas('tags', function ($query) use ($category) {
            $query->where('name', $category);
        })->get();
    }

    /**
     * Get badges by type.
     *
     * @param  string  $type  The badge type
     */
    public function getBadgesByType(string $type): Collection
    {
        return $this->model->whereHas('tags', function ($query) use ($type) {
            $query->where('type', $type);
        })->get();
    }

    /**
     * Get badge with conditions.
     *
     * @throws BadgeRepositoryException
     */
    public function getBadgeWithConditions(string $badgeId): Badge
    {
        try {
            return $this->model->with('conditions')->findOrFail($badgeId);
        } catch (ModelNotFoundException $e) {
            throw BadgeRepositoryException::notFound($badgeId);
        } catch (QueryException $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        } catch (\Exception $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get badge with user achievements.
     *
     * @throws BadgeRepositoryException
     */
    public function getBadgeWithUserAchievements(string $badgeId): Badge
    {
        try {
            return $this->model->with(['users' => function ($query) {
                $query->withPivot('earned_at');
            }])->findOrFail($badgeId);
        } catch (ModelNotFoundException $e) {
            throw BadgeRepositoryException::notFound($badgeId);
        } catch (QueryException $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        } catch (\Exception $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Get conditions by badge.
     *
     * @param  string  $badgeId  The badge ID
     *
     * @throws BadgeRepositoryException
     */
    public function getConditionsByBadge(string $badgeId): Collection
    {
        $badge = $this->findByIdOrFail($badgeId);

        return $badge->conditions;
    }

    /**
     * Get user achievements by badge.
     *
     * @param  string  $badgeId  The badge ID
     *
     * @throws BadgeRepositoryException
     */
    public function getUserAchievementsByBadge(string $badgeId): Collection
    {
        $badge = $this->findByIdOrFail($badgeId);

        return $badge->users;
    }

    /**
     * Get achievements count for badge.
     *
     * @param  string  $badgeId  The badge ID
     *
     * @throws BadgeRepositoryException
     */
    public function getAchievementsCount(string $badgeId): int
    {
        $badge = $this->findByIdOrFail($badgeId);

        return $badge->users()->count();
    }

    /**
     * Get badge achievement statistics.
     *
     * @param  string  $badgeId  The badge ID
     *
     * @throws BadgeRepositoryException
     */
    public function getBadgeAchievementStats(string $badgeId): array
    {
        try {
            $badge = $this->findByIdOrFail($badgeId);

            $totalUsers = User::count();
            $achievedUsers = $badge->users()->count();
            $achievementRate = $totalUsers > 0 ? ($achievedUsers / $totalUsers) * 100 : 0;

            $recentAchievements = $badge->users()
                ->wherePivot('earned_at', '>=', now()->subDays(30))
                ->count();

            return [
                'total_achievements' => $achievedUsers,
                'achievement_rate' => round($achievementRate, 2),
                'recent_achievements' => $recentAchievements,
                'total_users' => $totalUsers,
            ];
        } catch (\Exception $e) {
            throw BadgeRepositoryException::statisticsCalculationFailed($e->getMessage());
        }
    }

    /**
     * Get badge progress distribution.
     *
     * @param  string  $badgeId  The badge ID
     *
     * @throws BadgeRepositoryException
     */
    public function getBadgeProgressDistribution(string $badgeId): array
    {
        try {
            $badge = $this->findByIdOrFail($badgeId);

            $monthlyAchievements = $badge->users()
                ->selectRaw('DATE_FORMAT(user_badges.earned_at, "%Y-%m") as month, COUNT(*) as count')
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->pluck('count', 'month')
                ->toArray();

            return [
                'monthly_achievements' => $monthlyAchievements,
            ];
        } catch (\Exception $e) {
            throw BadgeRepositoryException::statisticsCalculationFailed($e->getMessage());
        }
    }

    /**
     * Get average completion time for badge.
     *
     * @param  string  $badgeId  The badge ID
     *
     * @throws BadgeRepositoryException
     */
    public function getAverageCompletionTime(string $badgeId): ?float
    {
        try {
            $badge = $this->findByIdOrFail($badgeId);

            // This would require additional logic to track when users started working towards the badge
            // For now, return null as we don't have start tracking
            return null;
        } catch (\Exception $e) {
            throw BadgeRepositoryException::statisticsCalculationFailed($e->getMessage());
        }
    }

    /**
     * Get badges by IDs.
     *
     * @param  array  $badgeIds  Array of badge IDs
     */
    public function getBadgesByIds(array $badgeIds): Collection
    {
        return $this->model->whereIn('id', $badgeIds)->get();
    }

    /**
     * Search badges by keyword.
     *
     * @param  string  $keyword  The search keyword
     * @param  array  $filters  Additional filters
     */
    public function searchBadges(string $keyword, array $filters = []): Collection
    {
        $query = $this->model->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
                ->orWhere('description', 'like', "%{$keyword}%");
        });

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['category'])) {
            $query->whereHas('tags', function ($q) use ($filters) {
                $q->where('name', $filters['category']);
            });
        }

        return $query->get();
    }

    /**
     * Get paginated badges.
     *
     * @param  int  $perPage  Items per page
     * @param  array  $filters  Additional filters
     */
    public function getPaginatedBadges(int $perPage = 15, array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->query();

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['search'])) {
            $query->where(function ($q) use ($filters) {
                $q->where('title', 'like', "%{$filters['search']}%")
                    ->orWhere('description', 'like', "%{$filters['search']}%");
            });
        }

        return $query->paginate($perPage);
    }

    /**
     * Get featured badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     */
    public function getFeaturedBadges(int $limit = 10): Collection
    {
        return $this->model->whereHas('tags', function ($query) {
            $query->where('name', 'featured');
        })->limit($limit)->get();
    }

    /**
     * Get popular badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     */
    public function getPopularBadges(int $limit = 10): Collection
    {
        return $this->model->withCount('users')
            ->orderBy('users_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get recent badges.
     *
     * @param  int  $limit  Maximum number of badges to return
     */
    public function getRecentBadges(int $limit = 10): Collection
    {
        return $this->model->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get badges by status and category.
     *
     * @param  string  $status  The badge status
     * @param  string  $category  The badge category
     */
    public function getBadgesByStatusAndCategory(string $status, string $category): Collection
    {
        return $this->model->where('status', $status)
            ->whereHas('tags', function ($query) use ($category) {
                $query->where('name', $category);
            })->get();
    }

    /**
     * Get badges with minimum achievements.
     *
     * @param  int  $minAchievements  Minimum number of achievements
     */
    public function getBadgesWithMinAchievements(int $minAchievements): Collection
    {
        return $this->model->withCount('users')
            ->having('users_count', '>=', $minAchievements)
            ->get();
    }

    /**
     * Get badges by difficulty level.
     *
     * @param  string  $difficulty  The difficulty level
     */
    public function getBadgesByDifficulty(string $difficulty): Collection
    {
        return $this->model->whereHas('tags', function ($query) use ($difficulty) {
            $query->where('name', $difficulty);
        })->get();
    }

    /**
     * Get badges by points range.
     *
     * @param  int  $minPoints  Minimum points
     * @param  int  $maxPoints  Maximum points
     */
    public function getBadgesByPointsRange(int $minPoints, int $maxPoints): Collection
    {
        // Since Badge model doesn't have points field, we'll use tags or conditions
        return $this->model->whereHas('conditions', function ($query) use ($minPoints, $maxPoints) {
            $query->where('condition_type', 'points_earned')
                ->whereJsonBetween('condition_data->minimum_points', [$minPoints, $maxPoints]);
        })->get();
    }

    /**
     * Get user badges.
     *
     * @param  string  $userId  The user ID
     */
    public function getUserBadges(string $userId): Collection
    {
        return $this->model->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['users' => function ($query) use ($userId) {
            $query->where('user_id', $userId)->withPivot('earned_at');
        }])->get();
    }

    /**
     * Get user badge progress.
     *
     * @param  string  $userId  The user ID
     * @param  string  $badgeId  The badge ID
     *
     * @throws BadgeRepositoryException
     */
    public function getUserBadgeProgress(string $userId, string $badgeId): array
    {
        try {
            $badge = $this->findByIdOrFail($badgeId);
            $hasEarned = $this->userHasBadge($userId, $badgeId);

            if ($hasEarned) {
                $earnedAt = $badge->users()->where('user_id', $userId)->first()?->pivot?->earned_at;

                return [
                    'earned' => true,
                    'earned_at' => $earnedAt,
                    'progress_percentage' => 100,
                ];
            }

            // Calculate progress based on conditions (simplified)
            $conditions = $badge->conditions;
            $totalConditions = $conditions->count();
            $completedConditions = 0; // This would require more complex logic

            $progressPercentage = $totalConditions > 0 ? ($completedConditions / $totalConditions) * 100 : 0;

            return [
                'earned' => false,
                'earned_at' => null,
                'progress_percentage' => $progressPercentage,
                'total_conditions' => $totalConditions,
                'completed_conditions' => $completedConditions,
            ];
        } catch (\Exception $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        }
    }

    /**
     * Check if user has badge.
     *
     * @param  string  $userId  The user ID
     * @param  string  $badgeId  The badge ID
     */
    public function userHasBadge(string $userId, string $badgeId): bool
    {
        return $this->model->whereHas('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('id', $badgeId)->exists();
    }

    /**
     * Get eligible badges for user.
     *
     * @param  string  $userId  The user ID
     */
    public function getEligibleBadgesForUser(string $userId): Collection
    {
        // Get badges that user doesn't have yet
        return $this->model->whereDoesntHave('users', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->where('status', BadgeStatus::ACTIVE->value)->get();
    }

    /**
     * Get badge leaderboard.
     *
     * @param  string  $badgeId  The badge ID
     * @param  int  $limit  Maximum number of users to return
     *
     * @throws BadgeRepositoryException
     */
    public function getBadgeLeaderboard(string $badgeId, int $limit = 10): Collection
    {
        try {
            $badge = $this->findByIdOrFail($badgeId);

            return $badge->users()
                ->withPivot('earned_at')
                ->orderBy('user_badges.earned_at', 'asc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            throw BadgeRepositoryException::databaseError($e->getMessage());
        }
    }
}
