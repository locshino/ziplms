<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\BadgeCondition;
use Illuminate\Database\Seeder;

class BadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create badge conditions first
        $conditions = [
            [
                'name' => 'Complete 5 Courses',
                'type' => 'course_completion',
                'operator' => '>=',
                'parameters' => ['course_count' => 5],
            ],
            [
                'name' => 'Score 90+ on Quiz',
                'type' => 'quiz_score',
                'operator' => '>=',
                'parameters' => ['min_score' => 90, 'quiz_count' => 1],
            ],
            [
                'name' => 'Perfect Assignment Grade',
                'type' => 'assignment_grade',
                'operator' => '=',
                'parameters' => ['min_grade' => 100, 'assignment_count' => 1],
            ],
            [
                'name' => 'High Enrollment',
                'type' => 'enrollment_count',
                'operator' => '>=',
                'parameters' => ['min_enrollments' => 20],
            ],
        ];

        $createdConditions = [];
        foreach ($conditions as $conditionData) {
            $createdConditions[] = BadgeCondition::create($conditionData);
        }

        // Create badges
        $badges = [
            [
                'title' => 'Course Master',
                'description' => 'Awarded for completing multiple courses successfully.',
                'award_status' => 'automatic',
                'conditions' => [$createdConditions[0]], // Complete 5 Courses
            ],
            [
                'title' => 'Quiz Champion',
                'description' => 'Awarded for achieving excellent quiz scores.',
                'award_status' => 'automatic',
                'conditions' => [$createdConditions[1]], // Score 90+ on Quiz
            ],
            [
                'title' => 'Perfect Student',
                'description' => 'Awarded for perfect assignment submissions.',
                'award_status' => 'automatic',
                'conditions' => [$createdConditions[2]], // Perfect Assignment Grade
            ],
            [
                'title' => 'Popular Teacher',
                'description' => 'Awarded to teachers with high student enrollment.',
                'award_status' => 'automatic',
                'conditions' => [$createdConditions[3]], // High Enrollment
            ],
            [
                'title' => 'Outstanding Achievement',
                'description' => 'Manually awarded for exceptional performance.',
                'award_status' => 'manual',
                'conditions' => [],
            ],
            [
                'title' => 'Academic Excellence',
                'description' => 'Awarded for meeting multiple academic criteria.',
                'award_status' => 'conditional',
                'conditions' => [$createdConditions[1], $createdConditions[2]], // Quiz + Assignment
            ],
        ];

        foreach ($badges as $badgeData) {
            $conditions = $badgeData['conditions'];
            unset($badgeData['conditions']);

            $badge = Badge::create($badgeData);

            // Attach conditions to badge
            foreach ($conditions as $condition) {
                $badge->conditions()->attach($condition->id);
            }
        }

        // Create additional random badges and conditions
        BadgeCondition::factory(10)->create();
        Badge::factory(15)->create()->each(function ($badge) {
            // Randomly attach 1-3 conditions to each badge
            $conditions = BadgeCondition::inRandomOrder()->limit(rand(1, 3))->get();
            $badge->conditions()->attach($conditions->pluck('id'));
        });
    }
}
