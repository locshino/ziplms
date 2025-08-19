<?php

namespace App\Services;

use App\Exceptions\Services\QuizServiceException;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentQuizAnswer;
use App\Repositories\Interfaces\QuizAttemptRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Services\Interfaces\QuizServiceInterface;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Quiz service implementation.
 *
 * Handles quiz business logic operations with proper exception handling,
 * caching, and transaction management.
 *
 * @throws QuizServiceException When service operations fail
 */
class QuizService extends BaseService implements QuizServiceInterface
{
    protected QuizAttemptRepositoryInterface $quizAttemptRepository;

    protected QuizCacheService $cacheService;

    /**
     * Constructor.
     */
    public function __construct(
        QuizRepositoryInterface $repository,
        QuizAttemptRepositoryInterface $quizAttemptRepository,
        QuizCacheService $cacheService
    ) {
        parent::__construct($repository);
        $this->quizAttemptRepository = $quizAttemptRepository;
        $this->cacheService = $cacheService;
    }

    /**
     * Get available quizzes for student.
     *
     * @throws QuizServiceException When student not found or database error occurs
     */
    public function getAvailableQuizzes(string $studentId): Collection
    {
        try {
            return $this->repository->getQuizzesByStudent($studentId);
        } catch (Exception $e) {
            Log::error('Failed to get available quizzes for student', [
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Start a new quiz attempt.
     *
     * @throws QuizServiceException When quiz not found, not available, or attempt creation fails
     */
    public function startQuizAttempt(string $quizId, string $studentId): QuizAttempt
    {
        try {
            // Get quiz with caching
            $quiz = $this->cacheService->getQuiz($quizId);
            if (! $quiz) {
                throw QuizServiceException::quizNotFound($quizId);
            }

            // Validate quiz is available
            $this->validateQuizAvailability($quiz);

            // Check if student can take this quiz
            $this->validateStudentCanTakeQuiz($quiz, $studentId);

            return DB::transaction(function () use ($quiz, $studentId) {
                try {
                    // Get existing attempts
                    $existingAttempts = $this->cacheService->getStudentAttempts($quiz->id, $studentId);

                    // Check for concurrent attempts
                    $activeAttempt = collect($existingAttempts)
                        ->where('status', 'in_progress')
                        ->first();

                    if ($activeAttempt) {
                        throw QuizServiceException::concurrentAttemptNotAllowed();
                    }

                    // Calculate next attempt number
                    $nextAttemptNumber = count($existingAttempts) + 1;

                    // Check attempt limit
                    if ($quiz->max_attempts && $nextAttemptNumber > $quiz->max_attempts) {
                        throw QuizServiceException::maxAttemptsExceeded();
                    }

                    // Create new attempt
                    $attempt = $this->quizAttemptRepository->create([
                        'quiz_id' => $quiz->id,
                        'student_id' => $studentId,
                        'attempt_number' => $nextAttemptNumber,
                        'status' => 'in_progress',
                        'started_at' => Carbon::now(),
                    ]);

                    // Cache the attempt
                    $this->cacheService->cacheAttempt($attempt);

                    // Invalidate student attempts cache
                    $this->cacheService->invalidateStudentAttemptsCache($quiz->id, $studentId);

                    Log::info('Quiz attempt started', [
                        'quiz_id' => $quiz->id,
                        'student_id' => $studentId,
                        'attempt_id' => $attempt->id,
                        'attempt_number' => $nextAttemptNumber,
                    ]);

                    return $attempt;
                } catch (\Exception $e) {
                    Log::error('Failed to start quiz attempt', [
                        'quiz_id' => $quiz->id,
                        'student_id' => $studentId,
                        'error' => $e->getMessage(),
                    ]);
                    throw $e;
                }
            });
        } catch (\Exception $e) {
            Log::error('Failed to start quiz attempt', [
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Validate quiz availability.
     *
     * @throws QuizServiceException When quiz is not available
     */
    private function validateQuizAvailability(Quiz $quiz): void
    {
        try {
            $now = Carbon::now();

            // Check if quiz is published
            if (! $quiz->is_published) {
                throw QuizServiceException::quizNotPublished();
            }

            // Check start time
            if ($quiz->start_at && $now->lt($quiz->start_at)) {
                throw QuizServiceException::quizNotStarted();
            }

            // Check end time
            if ($quiz->end_at && $now->gt($quiz->end_at)) {
                throw QuizServiceException::quizExpired();
            }
        } catch (Exception $e) {
            Log::error('Quiz availability validation failed', [
                'quiz_id' => $quiz->id,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Validate if student can take the quiz.
     *
     * @throws QuizServiceException When student cannot take the quiz
     */
    private function validateStudentCanTakeQuiz(Quiz $quiz, string $studentId): void
    {
        try {
            // Check if student is enrolled in the course
            $isEnrolled = DB::table('enrollments')
                ->where('course_id', $quiz->course_id)
                ->where('student_id', $studentId)
                ->exists();

            if (! $isEnrolled) {
                throw QuizServiceException::quizNotActive();
            }

            // Additional business logic can be added here
            // e.g., prerequisites, time restrictions, etc.
        } catch (Exception $e) {
            Log::error('Student quiz validation failed', [
                'quiz_id' => $quiz->id,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Continue an existing quiz attempt.
     *
     * @throws QuizServiceException When database error occurs
     */
    public function continueQuizAttempt(string $quizId, string $studentId): ?QuizAttempt
    {
        try {
            // Get incomplete attempt for the student
            $attempt = $this->quizAttemptRepository->getIncompleteAttempt($quizId, $studentId);

            if (! $attempt) {
                return null;
            }

            // Check if attempt is still valid (not expired)
            if ($attempt->quiz->time_limit) {
                $timeElapsed = Carbon::now()->diffInMinutes($attempt->started_at);
                if ($timeElapsed >= $attempt->quiz->time_limit) {
                    // Auto-submit if time limit exceeded
                    try {
                        // Get existing answers before auto-submit
                        $attemptWithAnswers = $this->quizAttemptRepository->getWithAnswers($attempt->id);
                        $existingAnswers = [];
                        if ($attemptWithAnswers && $attemptWithAnswers->answers) {
                            foreach ($attemptWithAnswers->answers as $answer) {
                                $existingAnswers[$answer->question_id] = $answer->answer_choice_id;
                            }
                        }
                        $this->submitQuizAttempt($attempt->id, $existingAnswers);
                    } catch (Exception $e) {
                        // If auto-submit fails, mark as expired manually
                        Log::warning('Auto-submit failed for expired attempt', [
                            'attempt_id' => $attempt->id,
                            'error' => $e->getMessage(),
                        ]);

                        // Update attempt status to expired
                        $this->quizAttemptRepository->updateById($attempt->id, [
                            'status' => 'expired',
                            'completed_at' => Carbon::now(),
                        ]);

                        // Clear cache
                        $this->cacheService->invalidateStudentAttemptsCache($attempt->quiz_id, $attempt->student_id);
                    }

                    return null;
                }
            }

            // Cache the attempt
            $this->cacheService->cacheAttempt($attempt);

            return $attempt;
        } catch (Exception $e) {
            Log::error('Failed to continue quiz attempt', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Submit quiz attempt with optimized bulk operations.
     *
     * @throws QuizServiceException When attempt not found, already completed, or submission fails
     */
    public function submitQuizAttempt(string $attemptId, array $answers): QuizAttempt
    {
        try {
            return DB::transaction(function () use ($attemptId, $answers) {
                // Get attempt with caching
                $cachedAttempt = $this->cacheService->getCachedAttempt($attemptId);

                if ($cachedAttempt) {
                    $attempt = new QuizAttempt($cachedAttempt);
                } else {
                    $attempt = $this->quizAttemptRepository->findById($attemptId);
                    if (! $attempt) {
                        throw QuizServiceException::quizAttemptNotFound();
                    }
                }

                // Validate attempt status
                if ($attempt->status !== 'in_progress') {
                    throw QuizServiceException::quizAttemptAlreadyCompleted();
                }

                // Validate answers format
                $this->validateAnswersFormat($answers);

                try {
                    // Get quiz questions for validation
                    $quizQuestions = $this->cacheService->getQuizQuestions($attempt->quiz_id);
                    $questionIds = collect($quizQuestions)->pluck('id')->toArray();

                    // Validate that all answers are for valid questions
                    foreach (array_keys($answers) as $questionId) {
                        if (! in_array($questionId, $questionIds)) {
                            throw QuizServiceException::invalidAnswerSubmission();
                        }
                    }

                    // Delete existing answers in bulk
                    $existingAnswerIds = StudentQuizAnswer::where('quiz_attempt_id', $attemptId)
                        ->pluck('id')
                        ->toArray();

                    if (! empty($existingAnswerIds)) {
                        StudentQuizAnswer::whereIn('id', $existingAnswerIds)->delete();
                    }

                    // Prepare answers for bulk insert
                    $answersToInsert = [];
                    foreach ($answers as $questionId => $answerChoiceIds) {
                        if (is_array($answerChoiceIds)) {
                            foreach ($answerChoiceIds as $answerChoiceId) {
                                $answersToInsert[] = [
                                    'id' => (string) \Illuminate\Support\Str::uuid(),
                                    'quiz_attempt_id' => $attemptId,
                                    'question_id' => $questionId,
                                    'answer_choice_id' => $answerChoiceId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        } else {
                            $answersToInsert[] = [
                                'id' => (string) \Illuminate\Support\Str::uuid(),
                                'quiz_attempt_id' => $attemptId,
                                'question_id' => $questionId,
                                'answer_choice_id' => $answerChoiceIds,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];
                        }
                    }

                    // Bulk insert answers
                    if (! empty($answersToInsert)) {
                        StudentQuizAnswer::insert($answersToInsert);
                    }

                    // Calculate score with optimized method
                    $score = $this->calculateScoreOptimized($attempt, $quizQuestions, $answersToInsert);

                    // Update attempt
                    $this->quizAttemptRepository->updateById($attemptId, [
                        'score' => $score,
                        'status' => 'completed',
                        'completed_at' => Carbon::now(),
                    ]);

                    // Get updated attempt
                    $updatedAttempt = $this->quizAttemptRepository->findById($attemptId);

                    // Invalidate caches
                    $this->cacheService->invalidateStudentAttemptsCache($attempt->quiz_id, $attempt->student_id);
                    Cache::forget($this->cacheService->getAttemptCacheKey($attemptId));

                    Log::info('Quiz attempt submitted successfully', [
                        'attempt_id' => $attemptId,
                        'quiz_id' => $attempt->quiz_id,
                        'student_id' => $attempt->student_id,
                        'score' => $score,
                        'answers_count' => count($answersToInsert),
                    ]);

                    return $updatedAttempt;
                } catch (\Exception $e) {
                    Log::error('Failed to submit quiz attempt', [
                        'attempt_id' => $attemptId,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    throw $e;
                }
            });
        } catch (\Exception $e) {
            Log::error('Failed to submit quiz attempt', [
                'attempt_id' => $attemptId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Validate answers format.
     *
     * @throws QuizServiceException When answers format is invalid
     */
    private function validateAnswersFormat(array $answers): void
    {
        try {
            if (empty($answers)) {
                throw QuizServiceException::invalidAnswerSubmission();
            }

            foreach ($answers as $questionId => $answerChoiceIds) {
                if (empty($questionId)) {
                    throw QuizServiceException::invalidAnswerSubmission();
                }

                if (is_array($answerChoiceIds)) {
                    if (empty($answerChoiceIds)) {
                        throw QuizServiceException::invalidAnswerSubmission();
                    }
                    foreach ($answerChoiceIds as $choiceId) {
                        if (empty($choiceId)) {
                            throw QuizServiceException::invalidAnswerSubmission();
                        }
                    }
                } else {
                    if (empty($answerChoiceIds)) {
                        throw QuizServiceException::invalidAnswerSubmission();
                    }
                }
            }
        } catch (Exception $e) {
            Log::error('Answer format validation failed', [
                'answers' => $answers,
                'error' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    /**
     * Get quiz attempt history for student.
     *
     * @throws QuizServiceException When quiz or student not found or database error occurs
     */
    public function getAttemptHistory(string $quizId, string $studentId): Collection
    {
        try {
            $cacheKey = $this->cacheService->getStudentAttemptsCacheKey($quizId, $studentId);

            return Cache::remember($cacheKey, 3600, function () use ($quizId, $studentId) {
                return $this->repository->getQuizAttemptsByStudent($quizId, $studentId);
            });
        } catch (Exception $e) {
            Log::error('Failed to get attempt history', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get quiz attempt with answers.
     *
     * @throws QuizServiceException When attempt not found or database error occurs
     */
    public function getAttemptWithAnswers(string $attemptId): ?QuizAttempt
    {
        try {
            $cacheKey = $this->cacheService->getAttemptWithAnswersCacheKey($attemptId);

            return Cache::remember($cacheKey, 1800, function () use ($attemptId) {
                return $this->quizAttemptRepository->getWithAnswers($attemptId);
            });
        } catch (Exception $e) {
            Log::error('Failed to get attempt with answers', [
                'attempt_id' => $attemptId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Check if student can take quiz.
     *
     * @throws QuizServiceException When database error occurs
     */
    public function canTakeQuiz(string $quizId, string $studentId): bool
    {
        try {
            $quiz = $this->repository->findById($quizId);

            if (! $quiz) {
                return false;
            }

            // Check if quiz is within time limits
            $now = Carbon::now();
            if ($quiz->start_at && $quiz->start_at->gt($now)) {
                return false;
            }
            if ($quiz->end_at && $quiz->end_at->lt($now)) {
                return false;
            }

            // Check attempt limits
            if ($quiz->max_attempts) {
                $attemptCount = $this->quizAttemptRepository->countStudentAttempts($quizId, $studentId);
                if ($attemptCount >= $quiz->max_attempts) {
                    return false;
                }
            }

            // Check if there's an incomplete attempt for single session quiz
            if ($quiz->is_single_session) {
                $incompleteAttempt = $this->quizAttemptRepository->getIncompleteAttempt($quizId, $studentId);
                if ($incompleteAttempt) {
                    return true; // Can continue the existing attempt
                }
            }

            return true;
        } catch (Exception $e) {
            Log::error('Failed to check if student can take quiz', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Calculate quiz score (optimized version).
     *
     * @throws QuizServiceException When score calculation fails
     */
    private function calculateScoreOptimized(QuizAttempt $attempt, Collection $questions, array $submittedAnswers): float
    {
        try {
            $totalPoints = $questions->sum('points');
            $earnedPoints = 0;

            // Group submitted answers by question
            $answersByQuestion = [];
            foreach ($submittedAnswers as $answer) {
                $questionId = $answer['question_id'];
                if (! isset($answersByQuestion[$questionId])) {
                    $answersByQuestion[$questionId] = [];
                }
                $answersByQuestion[$questionId][] = $answer['answer_choice_id'];
            }

            foreach ($questions as $question) {
                $correctChoiceIds = $question->answerChoices
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->toArray();

                $studentChoiceIds = $answersByQuestion[$question->id] ?? [];

                // Check if student answered correctly
                if (
                    count($correctChoiceIds) === count($studentChoiceIds) &&
                    empty(array_diff($correctChoiceIds, $studentChoiceIds))
                ) {
                    $earnedPoints += $question->points;
                }
            }

            return $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        } catch (Exception $e) {
            Log::error('Failed to calculate quiz score', [
                'attempt_id' => $attempt->id,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::calculationError($e->getMessage());
        }
    }

    /**
     * Calculate quiz score (legacy method).
     *
     * @throws QuizServiceException When score calculation fails
     */
    public function calculateScore(QuizAttempt $attempt): float
    {
        try {
            $attempt->load(['answers.question', 'answers.answerChoice', 'quiz.questions.answerChoices']);

            $totalPoints = 0;
            $earnedPoints = 0;

            foreach ($attempt->quiz->questions as $question) {
                $totalPoints += $question->points;

                $studentAnswers = $attempt->answers->where('question_id', $question->id);
                $correctChoices = $question->answerChoices->where('is_correct', true);
                $studentChoiceIds = $studentAnswers->pluck('answer_choice_id')->toArray();
                $correctChoiceIds = $correctChoices->pluck('id')->toArray();

                // Check if student answered correctly
                if (
                    count($correctChoiceIds) === count($studentChoiceIds) &&
                    empty(array_diff($correctChoiceIds, $studentChoiceIds))
                ) {
                    $earnedPoints += $question->points;
                }
            }

            return $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        } catch (Exception $e) {
            Log::error('Failed to calculate quiz score (legacy method)', [
                'attempt_id' => $attempt->id,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::calculationError($e->getMessage());
        }
    }

    /**
     * Get quizzes by course.
     *
     * @throws QuizServiceException When course not found or database error occurs
     */
    public function getQuizzesByCourse(string $courseId): Collection
    {
        try {
            return $this->repository->getQuizzesByCourse($courseId);
        } catch (Exception $e) {
            Log::error('Failed to get quizzes by course', [
                'course_id' => $courseId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get active quizzes.
     *
     * @throws QuizServiceException When database error occurs
     */
    public function getActiveQuizzes(): Collection
    {
        try {
            return $this->repository->getActiveQuizzes();
        } catch (Exception $e) {
            Log::error('Failed to get active quizzes', [
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get quiz with questions.
     *
     * @throws QuizServiceException When quiz not found or database error occurs
     */
    public function getQuizWithQuestions(string $quizId): ?Model
    {
        try {
            return $this->repository->getQuizWithQuestions($quizId);
        } catch (Exception $e) {
            Log::error('Failed to get quiz with questions', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get quiz with attempts and results.
     *
     * @throws QuizServiceException When quiz not found or database error occurs
     */
    public function getQuizWithAttempts(string $quizId): ?Model
    {
        try {
            return $this->repository->getQuizWithAttempts($quizId);
        } catch (Exception $e) {
            Log::error('Failed to get quiz with attempts', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get upcoming quizzes.
     *
     * @throws QuizServiceException When database error occurs
     */
    public function getUpcomingQuizzes(): Collection
    {
        try {
            return $this->repository->getUpcomingQuizzes();
        } catch (Exception $e) {
            Log::error('Failed to get upcoming quizzes', [
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get quizzes for student (alias for getAvailableQuizzes).
     */
    public function getQuizzesForStudent(string $studentId): Collection
    {
        return $this->getAvailableQuizzes($studentId);
    }

    /**
     * Check if student can take quiz (alias for canTakeQuiz).
     */
    public function canStudentTakeQuiz(string $quizId, string $studentId): bool
    {
        return $this->canTakeQuiz($quizId, $studentId);
    }

    /**
     * Check if quiz is currently active.
     */
    public function isQuizActive(string $quizId): bool
    {
        try {
            $quiz = $this->repository->findById($quizId);

            return $quiz ? $quiz->is_active : false;
        } catch (Exception $e) {
            Log::error('Failed to check if quiz is active', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Save quiz answers.
     */
    public function saveQuizAnswers(QuizAttempt $quizAttempt, array $answers): QuizAttempt
    {
        try {
            return DB::transaction(function () use ($quizAttempt, $answers) {
                foreach ($answers as $answer) {
                    StudentQuizAnswer::updateOrCreate(
                        [
                            'quiz_attempt_id' => $quizAttempt->id,
                            'question_id' => $answer['question_id'],
                        ],
                        [
                            'answer_choice_id' => $answer['answer_choice_id'],
                        ]
                    );
                }

                return $quizAttempt->fresh();
            });
        } catch (Exception $e) {
            Log::error('Failed to save quiz answers', [
                'attempt_id' => $quizAttempt->id,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get student attempts for a quiz.
     */
    public function getStudentAttempts(string $quizId, string $studentId): Collection
    {
        try {
            return $this->quizAttemptRepository->getStudentAttempts($quizId, $studentId);
        } catch (Exception $e) {
            Log::error('Failed to get student attempts', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get student's latest attempt for a quiz.
     */
    public function getLatestAttempt(string $quizId, string $studentId): ?QuizAttempt
    {
        try {
            return $this->quizAttemptRepository->getLatestAttempt($quizId, $studentId);
        } catch (Exception $e) {
            Log::error('Failed to get latest attempt', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get completed attempts for a quiz.
     */
    public function getCompletedAttemptsByQuiz(string $quizId): Collection
    {
        try {
            return $this->quizAttemptRepository->getCompletedAttemptsByQuiz($quizId);
        } catch (Exception $e) {
            Log::error('Failed to get completed attempts', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get remaining attempts for student.
     */
    public function getRemainingAttempts(string $quizId, string $studentId): ?int
    {
        try {
            $quiz = $this->repository->findById($quizId);
            if (! $quiz || ! $quiz->max_attempts) {
                return null;
            }

            $attemptCount = $this->quizAttemptRepository->countStudentAttempts($quizId, $studentId);

            return max(0, $quiz->max_attempts - $attemptCount);
        } catch (Exception $e) {
            Log::error('Failed to get remaining attempts', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get quiz performance statistics.
     */
    public function getQuizStats(string $quizId): array
    {
        try {
            $attempts = $this->quizAttemptRepository->getCompletedAttemptsByQuiz($quizId);

            if ($attempts->isEmpty()) {
                return [
                    'total_attempts' => 0,
                    'average_score' => 0,
                    'highest_score' => 0,
                    'lowest_score' => 0,
                    'completion_rate' => 0,
                ];
            }

            $scores = $attempts->pluck('score')->filter();

            return [
                'total_attempts' => $attempts->count(),
                'average_score' => $scores->avg(),
                'highest_score' => $scores->max(),
                'lowest_score' => $scores->min(),
                'completion_rate' => 100, // All attempts in this collection are completed
            ];
        } catch (Exception $e) {
            Log::error('Failed to get quiz stats', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get student's best score for a quiz.
     */
    public function getStudentBestScore(string $quizId, string $studentId): ?float
    {
        try {
            $attempts = $this->quizAttemptRepository->getStudentAttempts($quizId, $studentId);
            $completedAttempts = $attempts->whereIn('status', ['completed', 'submitted']);

            if ($completedAttempts->isEmpty()) {
                return null;
            }

            return $completedAttempts->max('score');
        } catch (Exception $e) {
            Log::error('Failed to get student best score', [
                'quiz_id' => $quizId,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get quizzes by course ID.
     */
    public function getQuizzesByCourseId(string $courseId): Collection
    {
        return $this->getQuizzesByCourse($courseId);
    }

    /**
     * Get paginated quizzes with course information.
     */
    public function getPaginatedQuizzesWithCourse(int $perPage = 15): \Illuminate\Pagination\LengthAwarePaginator
    {
        try {
            return $this->repository->getPaginatedWithCourse($perPage);
        } catch (Exception $e) {
            Log::error('Failed to get paginated quizzes with course', [
                'per_page' => $perPage,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get available quizzes for student (alias for getAvailableQuizzes).
     */
    public function getAvailableQuizzesForStudent(string $studentId): Collection
    {
        return $this->getAvailableQuizzes($studentId);
    }

    /**
     * Get all quiz attempts for a quiz.
     */
    public function getQuizAttempts(string $quizId): Collection
    {
        try {
            return $this->quizAttemptRepository->getQuizAttempts($quizId);
        } catch (Exception $e) {
            Log::error('Failed to get quiz attempts', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get quiz with questions and answer choices.
     */
    public function getQuizWithQuestionsAndChoices(string $quizId): ?Quiz
    {
        try {
            return $this->repository->getQuizWithQuestionsAndChoices($quizId);
        } catch (Exception $e) {
            Log::error('Failed to get quiz with questions and choices', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get questions by quiz ID.
     */
    public function getQuestionsByQuizId(string $quizId): Collection
    {
        try {
            return $this->repository->getQuestionsByQuizId($quizId);
        } catch (Exception $e) {
            Log::error('Failed to get questions by quiz ID', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get question with answer choices.
     */
    public function getQuestionWithAnswerChoices(string $questionId): ?Question
    {
        try {
            return Question::with('answerChoices')->find($questionId);
        } catch (Exception $e) {
            Log::error('Failed to get question with answer choices', [
                'question_id' => $questionId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }

    /**
     * Get questions with answer choices by quiz ID.
     */
    public function getQuestionsWithAnswerChoicesByQuizId(string $quizId): Collection
    {
        try {
            return Question::with('answerChoices')
                ->where('quiz_id', $quizId)
                ->get();
        } catch (Exception $e) {
            Log::error('Failed to get questions with answer choices by quiz ID', [
                'quiz_id' => $quizId,
                'error' => $e->getMessage(),
            ]);
            throw QuizServiceException::databaseError($e->getMessage());
        }
    }
}
