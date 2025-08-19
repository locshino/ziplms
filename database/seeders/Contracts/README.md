# Seeder Cache System

This project implements a caching system for database seeders to significantly reduce seeding time when running `php artisan migrate:fresh --seed`.

## ⚠️ Important Warning

**This cache system will NOT work effectively with database cache drivers** (such as `CACHE_STORE=database`). The cache mechanism relies on persistent cache storage that survives database resets. When using database cache driver, the cache is cleared during `migrate:fresh`, making the caching ineffective.

**Recommended cache drivers for this system:**

- `file`
- `redis` (recommended)
- `memcached`

## How It Works

The cache system uses Laravel's cache with the key prefix `_seeder_faker_` to store information about whether seeder data has already been created. This prevents redundant data generation and database operations.

## Key Features

- **Automatic Cache Detection**: Seeders automatically check if data already exists in both cache and database
- **Selective Seeding**: Only runs seeding logic if data doesn't exist or cache is invalid
- **Easy Cache Management**: Simple commands to clear cache when needed
- **Performance Optimization**: Dramatically reduces seeding time for subsequent runs

## Usage

### Normal Seeding

```bash
# First run - will create all data and cache it
php artisan migrate:fresh --seed

# Subsequent runs - will skip seeding if data exists
php artisan migrate:fresh --seed
```

### Force Regeneration

```bash
# Clear seeder cache to force regeneration
php artisan seeder:clear-cache

# Then run seeding again
php artisan migrate:fresh --seed
```

### Manual Cache Management

```php
// In your seeder or tinker
$seeder = new DatabaseSeeder();
$seeder->clearCache();
```

## Cache Keys

The system uses the following cache keys:

- `_seeder_faker_users` - User and role data
- `_seeder_faker_courses` - Course and tag data
- `_seeder_faker_assignments` - Assignment data
- `_seeder_faker_quizzes` - Quiz data
- `_seeder_faker_badges` - Badge data
- `_seeder_faker_badge_conditions` - Badge condition data

## Implementation Details

All seeders use the `HasCacheSeeder` trait which provides:

- `getCachedData($key, $callback)` - Execute callback only if cache miss
- `shouldSkipSeeding($cacheKey, $tableName)` - Check if seeding should be skipped
- `isTableEmpty($tableName)` - Check if database table is empty
- `clearCache($key)` - Clear specific or all seeder cache
- `getCacheDuration()` - Get cache duration (override to customize)

### Usage in Seeders

```php
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;

class YourSeeder extends Seeder
{
    use HasCacheSeeder;
    
    // Override cache duration if needed
    protected function getCacheDuration(): int
    {
        return 3600; // 1 hour
    }
    
    public function run(): void
    {
        if ($this->shouldSkipSeeding('your_cache_key', 'your_table')) {
            return;
        }
        
        $this->getCachedData('your_cache_key', function () {
            // Your seeding logic here
            return true;
        });
    }
}
```

## Benefits

- **Time Savings**: Reduces seeding time from minutes to seconds on subsequent runs
- **Development Efficiency**: Faster development cycles when frequently resetting database
- **Resource Optimization**: Reduces CPU and database load during development
- **Consistency**: Ensures same data structure across development environments

## Notes

- Cache is automatically invalidated when tables are empty (after `migrate:fresh`)
- The system is designed for development environments
- Production seeding should typically clear cache to ensure fresh data
- **Do not use database cache driver** - it will be cleared during `migrate:fresh`
- The trait approach provides more flexibility than inheritance
- Each seeder can customize its cache duration by overriding `getCacheDuration()`
