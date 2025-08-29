<?php

namespace Database\Seeders;

use App\Enums\Status\BadgeStatus;
use App\Enums\System\RoleSystem;
use App\Models\Badge;
use App\Models\User;
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    use HasCacheSeeder;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if badges already exist and cache is valid
        if ($this->shouldSkipSeeding('badges', 'badges')) {
            return;
        }

        // Get or create badges with caching
        $this->getCachedData('badges', function () {
            // Create 8 badges
            $badgeData = [
                [
                    'title' => 'First Steps',
                    'description' => 'Complete your first course',
                ],
                [
                    'title' => 'Quiz Master',
                    'description' => 'Score 100% on 5 quizzes',
                ],
                [
                    'title' => 'Assignment Ace',
                    'description' => 'Submit 10 assignments on time',
                ],
                [
                    'title' => 'Course Completionist',
                    'description' => 'Complete 5 courses',
                ],
                [
                    'title' => 'Learning Streak',
                    'description' => 'Login for 30 consecutive days',
                ],
                [
                    'title' => 'Knowledge Seeker',
                    'description' => 'Complete 100 quiz attempts',
                ],
                [
                    'title' => 'Perfect Score',
                    'description' => 'Achieve perfect scores on 3 assignments',
                ],
                [
                    'title' => 'Early Bird',
                    'description' => 'Submit 20 assignments before deadline',
                ],
            ];

            $badges = collect();
            foreach ($badgeData as $data) {
                $badge = Badge::factory()->create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'status' => BadgeStatus::ACTIVE->value,
                ]);
                $badges->push($badge);
            }

            // Get students to assign badges to
            $students = User::role(RoleSystem::STUDENT->value)->get();

            // Randomly assign badges to 50-100 students
            $studentsToReceiveBadges = $students->random(fake()->numberBetween(50, 100));

            foreach ($studentsToReceiveBadges as $student) {
                // Each student gets 1-3 random badges
                $studentBadges = $badges->random(fake()->numberBetween(1, 3));

                foreach ($studentBadges as $badge) {
                    // Check if student already has this badge
                    if (! $student->badges()->where('badge_id', $badge->id)->exists()) {
                        $student->badges()->attach($badge->id, [
                            'earned_at' => fake()->dateTimeBetween('-3 months', 'now'),
                            'status' => BadgeStatus::ACTIVE->value,
                        ]);
                    }
                }
            }

            return true;
        });
    }
}
