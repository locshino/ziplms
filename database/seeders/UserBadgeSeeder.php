<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\User;
use App\Models\UserBadge;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class UserBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $userIds = User::pluck('id');
        $badgeIds = Badge::pluck('id');

        if ($userIds->isEmpty() || $badgeIds->isEmpty()) {
            $this->command->info('Skipping UserBadgeSeeder: No users or badges found.');

            return;
        }

        // 1. Generate all possible user-badge pairs
        $allPossiblePairs = new Collection;
        foreach ($userIds as $userId) {
            foreach ($badgeIds as $badgeId) {
                $allPossiblePairs->push([
                    'user_id' => $userId,
                    'badge_id' => $badgeId,
                ]); // Add each combination to the collection
            }
        }

        // 2. Shuffle the list and take a sample (e.g., 50 pairs)
        // This ensures each pair is unique if the sample size is less than or equal to the total possible pairs.
        $pairsToInsert = $allPossiblePairs->shuffle()->take(50)->map(function ($pair) {
            // Add UUID and timestamps
            $pair['id'] = Str::uuid()->toString(); // Manually add UUID because we are using `insert`
            $pair['awarded_at'] = now();
            $pair['created_at'] = now();
            $pair['updated_at'] = now();

            return $pair;
        })->all();

        // 3. Truncate old data and bulk insert for better performance
        UserBadge::query()->truncate();
        UserBadge::insert($pairsToInsert);

        $this->command->info('Created '.count($pairsToInsert).' user badges.');
    }
}
