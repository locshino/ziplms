<?php

namespace Database\Seeders;

use App\Enums\Status\BadgeConditionStatus;
use App\Models\Badge;
use App\Models\BadgeCondition;
use App\Models\Question;
use App\Models\QuizAttempt;
use App\Models\StudentQuizAnswer;
use Database\Seeders\Contracts\HasCacheSeeder;
use Illuminate\Database\Seeder;

class AdditionalDataSeeder extends Seeder
{
    use HasCacheSeeder;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if badge conditions already exist and cache is valid
        if ($this->shouldSkipSeeding('badge_conditions', 'badge_conditions')) {
            return;
        }

        // Get or create badge conditions with caching
        $this->getCachedData('badge_conditions', function () {
            $this->createBadgeConditions();
            $this->createStudentQuizAnswers();
            return true;
        });
    }

    /**
     * Create badge conditions and link them to badges.
     */
    private function createBadgeConditions(): void
    {
        $badges = Badge::all();
        
        // Create badge conditions
        $conditions = collect();
        
        // Course completion conditions
        for ($i = 0; $i < 3; $i++) {
            $condition = BadgeCondition::factory()->create([
                'title' => 'Complete ' . fake()->numberBetween(1, 5) . ' Courses',
                'condition_type' => 'course_completion',
                'status' => BadgeConditionStatus::ACTIVE->value,
            ]);
            $conditions->push($condition);
        }
        
        // Quiz score conditions
        for ($i = 0; $i < 3; $i++) {
            $condition = BadgeCondition::factory()->create([
                'title' => 'Score ' . fake()->numberBetween(80, 100) . '% on Quizzes',
                'condition_type' => 'quiz_score',
                'status' => BadgeConditionStatus::ACTIVE->value,
            ]);
            $conditions->push($condition);
        }
        
        // Assignment submission conditions
        for ($i = 0; $i < 2; $i++) {
            $condition = BadgeCondition::factory()->create([
                'title' => 'Submit ' . fake()->numberBetween(5, 20) . ' Assignments',
                'condition_type' => 'assignment_submission',
                'status' => BadgeConditionStatus::ACTIVE->value,
            ]);
            $conditions->push($condition);
        }
        
        // Login streak conditions
        $condition = BadgeCondition::factory()->create([
            'title' => 'Login for ' . fake()->numberBetween(7, 30) . ' Consecutive Days',
            'condition_type' => 'login_streak',
            'status' => BadgeConditionStatus::ACTIVE->value,
        ]);
        $conditions->push($condition);
        
        // Points earned conditions
        $condition = BadgeCondition::factory()->create([
            'title' => 'Earn ' . fake()->numberBetween(100, 1000) . ' Points',
            'condition_type' => 'points_earned',
            'status' => BadgeConditionStatus::ACTIVE->value,
        ]);
        $conditions->push($condition);
        
        // Link conditions to badges
        foreach ($badges as $badge) {
            $badgeConditions = $conditions->random(fake()->numberBetween(1, 3));
            foreach ($badgeConditions as $condition) {
                $badge->conditions()->attach($condition->id, [
                    'status' => BadgeConditionStatus::ACTIVE->value,
                ]);
            }
        }
    }

    /**
     * Create student quiz answers for completed quiz attempts.
     */
    private function createStudentQuizAnswers(): void
    {
        // Get completed and graded quiz attempts
        $quizAttempts = QuizAttempt::whereIn('status', ['completed', 'graded'])->get();
        
        foreach ($quizAttempts as $attempt) {
            $quiz = $attempt->quiz;
            $questions = $quiz->questions;
            
            foreach ($questions as $question) {
                // 70% chance of correct answer, 30% chance of incorrect
                $isCorrect = fake()->boolean(70);
                
                if ($isCorrect) {
                    $answerChoice = $question->answerChoices()->where('is_correct', true)->first();
                } else {
                    $answerChoice = $question->answerChoices()->where('is_correct', false)->first();
                }
                
                if ($answerChoice) {
                    StudentQuizAnswer::factory()->create([
                        'quiz_attempt_id' => $attempt->id,
                        'question_id' => $question->id,
                        'answer_choice_id' => $answerChoice->id,
                    ]);
                }
            }
        }
    }
}