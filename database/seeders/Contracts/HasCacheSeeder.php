<?php

namespace Database\Seeders\Contracts;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait HasCacheSeeder
{
    /**
     * Get cache duration in seconds.
     * Override this method in your seeder to customize cache duration.
     */
    protected function getCacheDuration(): int
    {
        return 86400; // 24 hours
    }

    /**
     * Get cached data or execute callback if cache miss.
     */
    protected function getCachedData(string $key, callable $callback)
    {
        $cacheKey = "_seeder_faker_{$key}";

        return Cache::remember($cacheKey, $this->getCacheDuration(), $callback);
    }

    /**
     * Get cached models collection.
     */
    protected function getCachedModels(string $key, callable $callback)
    {
        return $this->getCachedData($key, $callback);
    }

    /**
     * Clear specific cache key.
     */
    protected function clearCache(?string $key = null): void
    {
        if ($key) {
            Cache::forget("_seeder_faker_{$key}");
        } else {
            // Clear all seeder cache keys
            $cacheKeys = [
                '_seeder_faker_users',
                '_seeder_faker_roles',
                '_seeder_faker_courses',
                '_seeder_faker_tags',
                '_seeder_faker_assignments',
                '_seeder_faker_quizzes',
                '_seeder_faker_badges',
                '_seeder_faker_badge_conditions',
            ];

            foreach ($cacheKeys as $cacheKey) {
                Cache::forget($cacheKey);
            }
        }
    }

    /**
     * Check if a database table is empty.
     */
    protected function isTableEmpty(string $tableName): bool
    {
        return DB::table($tableName)->count() === 0;
    }

    /**
     * Determine if seeding should be skipped based on cache and database state.
     */
    protected function shouldSkipSeeding(string $cacheKey, string $tableName): bool
    {
        $isCacheDriverDatabase = config('cache.default') === 'database';
        // If table is empty, we should seed regardless of cache
        if ($this->isTableEmpty($tableName) && ! $isCacheDriverDatabase) {
            return false;
        }

        // If table has data and cache exists, skip seeding
        $fullCacheKey = "_seeder_faker_{$cacheKey}";

        return Cache::has($fullCacheKey);
    }
}
