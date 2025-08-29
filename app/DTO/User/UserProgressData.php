<?php

namespace App\DTO\User;

use App\DTO\Concerns\InteractsWithArray;
use Carbon\Carbon;

/**
 * Data Transfer Object for user learning progress data.
 *
 * Contains comprehensive progress tracking information
 * for user's learning activities and achievements.
 */
class UserProgressData
{
    use InteractsWithArray;

    public function __construct(
        public mixed $userId,
        public mixed $courseId = null,
        public int $completedLessons = 0,
        public int $totalLessons = 0,
        public int $completedAssignments = 0,
        public int $totalAssignments = 0,
        public int $completedQuizzes = 0,
        public int $totalQuizzes = 0,
        public float $averageQuizScore = 0.0,
        public float $assignmentSubmissionRate = 0.0,
        public int $totalBadgesEarned = 0,
        public int $totalPointsEarned = 0,
        public ?Carbon $lastActivityAt = null,
        public ?Carbon $enrolledAt = null,
        public ?Carbon $completedAt = null,
        public array $recentActivities = [],
        public array $upcomingDeadlines = [],
        public array $earnedBadges = [],
        public array $courseProgress = []
    ) {}

    /**
     * Calculate overall completion percentage.
     */
    public function getOverallCompletionPercentage(): float
    {
        $totalItems = $this->totalLessons + $this->totalAssignments + $this->totalQuizzes;
        $completedItems = $this->completedLessons + $this->completedAssignments + $this->completedQuizzes;

        if ($totalItems === 0) {
            return 0.0;
        }

        return round(($completedItems / $totalItems) * 100, 2);
    }

    /**
     * Calculate lesson completion percentage.
     */
    public function getLessonCompletionPercentage(): float
    {
        if ($this->totalLessons === 0) {
            return 0.0;
        }

        return round(($this->completedLessons / $this->totalLessons) * 100, 2);
    }

    /**
     * Calculate assignment completion percentage.
     */
    public function getAssignmentCompletionPercentage(): float
    {
        if ($this->totalAssignments === 0) {
            return 0.0;
        }

        return round(($this->completedAssignments / $this->totalAssignments) * 100, 2);
    }

    /**
     * Calculate quiz completion percentage.
     */
    public function getQuizCompletionPercentage(): float
    {
        if ($this->totalQuizzes === 0) {
            return 0.0;
        }

        return round(($this->completedQuizzes / $this->totalQuizzes) * 100, 2);
    }

    /**
     * Check if user has completed the course.
     */
    public function isCourseCompleted(): bool
    {
        return $this->completedAt !== null || $this->getOverallCompletionPercentage() >= 100.0;
    }

    /**
     * Check if user is actively learning (activity within specified days).
     */
    public function isActivelyLearning(int $beforeNumOfDays = 7): bool
    {
        if ($this->lastActivityAt === null) {
            return false;
        }

        return $this->lastActivityAt->diffInDays(now()) <= $beforeNumOfDays;
    }

    /**
     * Get learning streak in days.
     */
    public function getLearningStreak(int $streakDays = 7): int
    {
        // This would typically be calculated based on daily activity records
        // For now, return a simple calculation based on recent activities
        return count(array_filter($this->recentActivities, function ($activity) use ($streakDays) {
            return isset($activity['date']) &&
                   Carbon::parse($activity['date'])->diffInDays(now()) <= $streakDays;
        }));
    }

    /**
     * Get performance level based on completion and scores.
     */
    public function getPerformanceLevel(
        int $excellentCompletion = 90,
        int $excellentScore = 90,
        int $goodCompletion = 70,
        int $goodScore = 80,
        int $averageCompletion = 50,
        int $averageScore = 70,
        int $belowAverageCompletion = 30,
        int $belowAverageScore = 60
    ): string {
        $completion = $this->getOverallCompletionPercentage();
        $avgScore = $this->averageQuizScore;

        if ($completion >= $excellentCompletion && $avgScore >= $excellentScore) {
            return 'excellent';
        } elseif ($completion >= $goodCompletion && $avgScore >= $goodScore) {
            return 'good';
        } elseif ($completion >= $averageCompletion && $avgScore >= $averageScore) {
            return 'average';
        } elseif ($completion >= $belowAverageCompletion || $avgScore >= $belowAverageScore) {
            return 'below_average';
        } else {
            return 'poor';
        }
    }

    /**
     * Get time spent learning (estimated based on activities).
     */
    public function getEstimatedTimeSpent(int $lessonMinutes = 30, int $assignmentMinutes = 60, int $quizMinutes = 15): int
    {
        // Estimate based on completed items (in minutes)
        $lessonTime = $this->completedLessons * $lessonMinutes;
        $assignmentTime = $this->completedAssignments * $assignmentMinutes;
        $quizTime = $this->completedQuizzes * $quizMinutes;

        return $lessonTime + $assignmentTime + $quizTime;
    }

    /**
     * Get upcoming deadlines count.
     */
    public function getUpcomingDeadlinesCount(): int
    {
        return count($this->upcomingDeadlines);
    }

    /**
     * Get overdue items count.
     */
    public function getOverdueItemsCount(): int
    {
        return count(array_filter($this->upcomingDeadlines, function ($deadline) {
            return isset($deadline['due_date']) &&
                   Carbon::parse($deadline['due_date'])->isPast();
        }));
    }

    /**
     * Get progress summary for display.
     *
     * @return array<string, mixed>
     */
    public function getProgressSummary(): array
    {
        return [
            'user_id' => $this->userId,
            'course_id' => $this->courseId,
            'overall_completion' => $this->getOverallCompletionPercentage(),
            'lesson_completion' => $this->getLessonCompletionPercentage(),
            'assignment_completion' => $this->getAssignmentCompletionPercentage(),
            'quiz_completion' => $this->getQuizCompletionPercentage(),
            'average_quiz_score' => $this->averageQuizScore,
            'assignment_submission_rate' => $this->assignmentSubmissionRate,
            'total_badges_earned' => $this->totalBadgesEarned,
            'total_points_earned' => $this->totalPointsEarned,
            'performance_level' => $this->getPerformanceLevel(),
            'is_course_completed' => $this->isCourseCompleted(),
            'is_actively_learning' => $this->isActivelyLearning(),
            'learning_streak' => $this->getLearningStreak(),
            'estimated_time_spent' => $this->getEstimatedTimeSpent(),
            'upcoming_deadlines_count' => $this->getUpcomingDeadlinesCount(),
            'overdue_items_count' => $this->getOverdueItemsCount(),
            'last_activity_at' => $this->lastActivityAt?->toISOString(),
            'enrolled_at' => $this->enrolledAt?->toISOString(),
            'completed_at' => $this->completedAt?->toISOString(),
        ];
    }

    /**
     * Get detailed progress data.
     *
     * @return array<string, mixed>
     */
    public function getDetailedProgress(): array
    {
        return array_merge($this->getProgressSummary(), [
            'recent_activities' => $this->recentActivities,
            'upcoming_deadlines' => $this->upcomingDeadlines,
            'earned_badges' => $this->earnedBadges,
            'course_progress' => $this->courseProgress,
        ]);
    }

    /**
     * Check if progress data is for a specific course.
     */
    public function isForSpecificCourse(): bool
    {
        return $this->courseId !== null;
    }

    /**
     * Check if user has any progress.
     */
    public function hasProgress(): bool
    {
        return $this->completedLessons > 0 ||
               $this->completedAssignments > 0 ||
               $this->completedQuizzes > 0 ||
               $this->totalBadgesEarned > 0;
    }
}
