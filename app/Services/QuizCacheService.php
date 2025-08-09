<?php

namespace App\Services;

use App\Exceptions\Services\QuizServiceException;
use App\Models\AnswerChoice;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class QuizCacheService
{
    private const CACHE_TTL = 3600; // 1 hour

    private const QUIZ_PREFIX = 'quiz:';

    private const QUESTIONS_PREFIX = 'quiz_questions:';

    private const ATTEMPTS_PREFIX = 'quiz_attempts:';

    private const STUDENT_ATTEMPTS_PREFIX = 'student_attempts:';

    private const QUIZ_STATS_PREFIX = 'quiz_stats:';

    /**
     * Get quiz with caching.
     */
    public function getQuiz(string $quizId): ?Quiz
    {
        try {
            return Cache::remember(
                self::QUIZ_PREFIX.$quizId,
                self::CACHE_TTL,
                fn () => Quiz::with(['course'])->find($quizId)
            );
        } catch (\Exception $e) {
            Log::error('Quiz cache error', ['quiz_id' => $quizId, 'error' => $e->getMessage()]);
            throw QuizServiceException::cacheError('get_quiz');
        }
    }

    /**
     * Get quiz questions with caching.
     */
    public function getQuizQuestions(string $quizId)
    {
        try {
            $cachedData = Cache::remember(
                self::QUESTIONS_PREFIX.$quizId,
                self::CACHE_TTL,
                fn () => Question::where('quiz_id', $quizId)
                    ->with(['answerChoices' => function ($query) {
                        $query->orderBy('id');
                    }])
                    ->orderBy('id')
                    ->get()
                    ->toArray()
            );

            Log::info('Quiz questions cached data', [
                'quiz_id' => $quizId,
                'cached_data_count' => count($cachedData),
                'first_question_structure' => $cachedData[0] ?? null,
                'cached_data_keys' => array_keys($cachedData[0] ?? []),
            ]);

            // Convert array back to Eloquent Collection for compatibility
            $questions = collect($cachedData)->map(function ($questionData) {
                $question = new Question;
                $question->fill($questionData);
                $question->id = $questionData['id'];
                $question->exists = true;

                $answerChoicesKey = $questionData['answer_choices'] ?? $questionData['answerChoices'] ?? [];
                $question->answerChoices = collect($answerChoicesKey)->map(function ($choiceData) {
                    $choice = new AnswerChoice;
                    $choice->fill($choiceData);
                    $choice->id = $choiceData['id'];
                    $choice->exists = true;

                    return $choice;
                });

                Log::info('Question mapped', [
                    'question_id' => $question->id,
                    'question_id_type' => gettype($question->id),
                    'answer_choices_count' => $question->answerChoices->count(),
                ]);

                return $question;
            });

            // Convert to Eloquent Collection
            return new \Illuminate\Database\Eloquent\Collection($questions);
        } catch (\Exception $e) {
            Log::error('Quiz questions cache error', ['quiz_id' => $quizId, 'error' => $e->getMessage()]);
            throw QuizServiceException::cacheError('get_quiz_questions');
        }
    }

    /**
     * Get student quiz attempts with caching.
     */
    public function getStudentAttempts(string $quizId, string $studentId): array
    {
        try {
            $cacheKey = self::STUDENT_ATTEMPTS_PREFIX.$quizId.':'.$studentId;

            return Cache::remember(
                $cacheKey,
                300, // 5 minutes for attempts
                fn () => QuizAttempt::where('quiz_id', $quizId)
                    ->where('student_id', $studentId)
                    ->orderBy('attempt_number', 'desc')
                    ->get()
                    ->toArray()
            );
        } catch (\Exception $e) {
            Log::error('Student attempts cache error', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::cacheError('get_student_attempts');
        }
    }

    /**
     * Get quiz statistics with caching.
     */
    public function getQuizStats(string $quizId): array
    {
        try {
            return Cache::remember(
                self::QUIZ_STATS_PREFIX.$quizId,
                1800, // 30 minutes
                function () use ($quizId) {
                    $attempts = QuizAttempt::where('quiz_id', $quizId)
                        ->where('status', 'completed')
                        ->get();

                    $totalAttempts = $attempts->count();
                    $averageScore = $totalAttempts > 0 ? $attempts->avg('score') : 0;
                    $maxScore = $totalAttempts > 0 ? $attempts->max('score') : 0;
                    $minScore = $totalAttempts > 0 ? $attempts->min('score') : 0;
                    $passRate = $totalAttempts > 0 ?
                        $attempts->where('score', '>=', 70)->count() / $totalAttempts * 100 : 0;

                    return [
                        'total_attempts' => $totalAttempts,
                        'average_score' => round($averageScore, 2),
                        'max_score' => $maxScore,
                        'min_score' => $minScore,
                        'pass_rate' => round($passRate, 2),
                        'updated_at' => Carbon::now()->toISOString(),
                    ];
                }
            );
        } catch (\Exception $e) {
            Log::error('Quiz stats cache error', ['quiz_id' => $quizId, 'error' => $e->getMessage()]);
            throw QuizServiceException::cacheError('get_quiz_stats');
        }
    }

    /**
     * Invalidate quiz cache.
     */
    public function invalidateQuizCache(string $quizId): void
    {
        try {
            $keys = [
                self::QUIZ_PREFIX.$quizId,
                self::QUESTIONS_PREFIX.$quizId,
                self::QUIZ_STATS_PREFIX.$quizId,
            ];

            foreach ($keys as $key) {
                Cache::forget($key);
            }

            // Invalidate student attempts cache (pattern-based)
            $this->invalidatePatternCache(self::STUDENT_ATTEMPTS_PREFIX.$quizId.':*');
        } catch (\Exception $e) {
            Log::error('Cache invalidation error', ['quiz_id' => $quizId, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Invalidate student attempts cache.
     */
    public function invalidateStudentAttemptsCache(string $quizId, string $studentId): void
    {
        try {
            $cacheKey = self::STUDENT_ATTEMPTS_PREFIX.$quizId.':'.$studentId;
            Cache::forget($cacheKey);

            // Also invalidate quiz stats as new attempt affects statistics
            Cache::forget(self::QUIZ_STATS_PREFIX.$quizId);
        } catch (\Exception $e) {
            Log::error('Student attempts cache invalidation error', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Warm up cache for a quiz.
     */
    public function warmUpQuizCache(string $quizId): void
    {
        try {
            // Pre-load quiz data
            $this->getQuiz($quizId);
            $this->getQuizQuestions($quizId);
            $this->getQuizStats($quizId);
        } catch (\Exception $e) {
            Log::error('Cache warm-up error', ['quiz_id' => $quizId, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Get cache key for quiz attempt.
     */
    public function getAttemptCacheKey(string $attemptId): string
    {
        return self::ATTEMPTS_PREFIX.$attemptId;
    }

    /**
     * Get cache key for student attempts.
     */
    public function getStudentAttemptsCacheKey(string $quizId, string $studentId): string
    {
        return self::STUDENT_ATTEMPTS_PREFIX.$quizId.':'.$studentId;
    }

    /**
     * Get cache key for attempt with answers.
     */
    public function getAttemptWithAnswersCacheKey(string $attemptId): string
    {
        return 'attempt_with_answers:'.$attemptId;
    }

    /**
     * Get cache key for quiz questions.
     */
    public function getQuestionsCacheKey(string $quizId): string
    {
        return self::QUESTIONS_PREFIX.$quizId;
    }

    /**
     * Cache quiz attempt.
     */
    public function cacheAttempt(QuizAttempt $attempt): void
    {
        try {
            $cacheKey = $this->getAttemptCacheKey($attempt->id);
            Cache::put($cacheKey, $attempt->toArray(), 1800); // 30 minutes
        } catch (\Exception $e) {
            Log::error('Attempt cache error', ['attempt_id' => $attempt->id, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Get cached attempt.
     */
    public function getCachedAttempt(string $attemptId): ?array
    {
        try {
            $cacheKey = $this->getAttemptCacheKey($attemptId);

            return Cache::get($cacheKey);
        } catch (\Exception $e) {
            Log::error('Get cached attempt error', ['attempt_id' => $attemptId, 'error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Invalidate pattern-based cache keys.
     */
    private function invalidatePatternCache(string $pattern): void
    {
        // This is a simplified implementation
        // In production, you might want to use Redis SCAN or similar
        // For now, we'll just log the pattern for manual cleanup
        Log::info('Pattern cache invalidation needed', ['pattern' => $pattern]);
    }

    /**
     * Clear all quiz-related cache.
     */
    public function clearAllQuizCache(): void
    {
        try {
            // In a real implementation, you'd want to use cache tags or patterns
            Cache::flush();
            Log::info('All quiz cache cleared');
        } catch (\Exception $e) {
            Log::error('Clear all cache error', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Get cache statistics.
     */
    public function getCacheStats(): array
    {
        // This would depend on your cache driver
        // For Redis, you could use INFO command
        // For now, return basic info
        return [
            'cache_driver' => config('cache.default'),
            'ttl' => self::CACHE_TTL,
            'prefixes' => [
                'quiz' => self::QUIZ_PREFIX,
                'questions' => self::QUESTIONS_PREFIX,
                'attempts' => self::ATTEMPTS_PREFIX,
                'student_attempts' => self::STUDENT_ATTEMPTS_PREFIX,
                'stats' => self::QUIZ_STATS_PREFIX,
            ],
        ];
    }
}
