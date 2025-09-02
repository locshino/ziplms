<?php

namespace App\Services;

use App\Enums\Status\QuizAttemptStatus;
use App\Exceptions\Services\QuizServiceException;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentQuizAnswer;
use App\Repositories\Interfaces\QuizAttemptRepositoryInterface;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Services\Interfaces\QuizCacheServiceInterface;
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

    protected QuizCacheServiceInterface $cacheService;

    /**
     * Constructor.
     */
    public function __construct(
        QuizRepositoryInterface $repository,
        QuizAttemptRepositoryInterface $quizAttemptRepository,
        QuizCacheServiceInterface $cacheService
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

                    // Check attempt limit (0 or null means unlimited)
                    if ($quiz->max_attempts !== null && $quiz->max_attempts > 0 && $nextAttemptNumber > $quiz->max_attempts) {
                        throw QuizServiceException::maxAttemptsExceeded();
                    }

                    // Create new attempt
                    $attempt = $this->quizAttemptRepository->create([
                        'quiz_id' => $quiz->id,
                        'student_id' => $studentId,
                        'attempt_number' => $nextAttemptNumber,
                        'status' => 'in_progress',
                        'start_at' => Carbon::now(),
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

            // Check if quiz is active
            if (! $quiz->is_active) {
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
            // Check if user exists and get user instance
            $user = \App\Models\User::find($studentId);
            if (! $user) {
                throw QuizServiceException::quizNotActive();
            }

            // Check if user is super admin or admin - they can take any quiz
            if ($user->hasRole(['super_admin', 'admin'])) {
                return; // Super admin and admin can take any quiz
            }

            // Check if user is a student
            if (! \App\Libs\Roles\RoleHelper::isStudent($user)) {
                throw QuizServiceException::quizNotActive();
            }

            // Check if student is enrolled in any course that contains this quiz
            $isEnrolledInQuizCourse = $quiz->courses()
                ->whereHas('users', function ($query) use ($studentId) {
                    $query->where('user_id', $studentId);
                })
                ->exists();

            if (! $isEnrolledInQuizCourse) {
                throw QuizServiceException::quizNotActive();
            }

            // Additional business logic can be added here
            // e.g., prerequisites, time restrictions, etc.
        } catch (Exception $e) {
            Log::error('Student quiz validation failed', [
                'quiz_id' => $quiz->id,
                'student_id' => $studentId,
                'error' => $e->getMessage(),
                'is_enrolled' => isset($isEnrolledInQuizCourse) ? $isEnrolledInQuizCourse : false,
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
            if ($attempt->quiz->time_limit_minutes) {
                $startTime = Carbon::parse($attempt->start_at);
                $timeLimit = $attempt->quiz->time_limit_minutes;
                $endTime = $startTime->copy()->addMinutes($timeLimit);
                $now = Carbon::now();

                if ($now->gte($endTime)) {
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
                            'end_at' => Carbon::now(),
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
                if ($attempt->status->value !== 'in_progress') {
                    throw QuizServiceException::quizAttemptAlreadyCompleted();
                }

                // Validate answers format
                $this->validateAnswersFormat($answers);

                try {
                    // Get quiz questions for validation
                    $quizQuestions = $this->cacheService->getQuizQuestions($attempt->quiz_id);

                    // Fallback to database if cache is empty, load with answerChoices for JSON preparation
                    if (empty($quizQuestions) || $quizQuestions->isEmpty()) {
                        $quiz = Quiz::find($attempt->quiz_id);
                        $quizQuestions = $quiz ? $quiz->questions()->with('answerChoices')->get() : collect();
                    } else {
                        // If using cached questions, ensure answerChoices are loaded
                        $quizQuestions = Question::with('answerChoices')
                            ->whereIn('id', $quizQuestions->pluck('id'))
                            ->get();
                    }

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
                        // Skip null or empty answers
                        if ($answerChoiceIds === null || $answerChoiceIds === '') {
                            continue;
                        }

                        if (is_array($answerChoiceIds)) {
                            // Skip empty arrays
                            if (empty($answerChoiceIds)) {
                                continue;
                            }
                            foreach ($answerChoiceIds as $answerChoiceId) {
                                // Skip empty choice IDs
                                if (empty($answerChoiceId)) {
                                    continue;
                                }
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
                            // Skip empty single choice answers
                            if (empty($answerChoiceIds)) {
                                continue;
                            }
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

                    // Prepare answers JSON for storage
                    $answersJson = $this->prepareAnswersJson($answers, $quizQuestions);

                    // Update attempt
                    $this->quizAttemptRepository->updateById($attemptId, [
                        'points' => $score,
                        'status' => QuizAttemptStatus::COMPLETED,
                        'end_at' => Carbon::now(),
                        'answers' => $answersJson,
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
            // Allow empty answers - student can submit without answering any questions
            if (empty($answers)) {
                return;
            }

            foreach ($answers as $questionId => $answerChoiceIds) {
                // Validate question ID is not empty
                if (empty($questionId)) {
                    throw QuizServiceException::invalidAnswerSubmission();
                }

                // Skip validation if answer is null or empty (unanswered question)
                if ($answerChoiceIds === null || $answerChoiceIds === '') {
                    continue;
                }

                if (is_array($answerChoiceIds)) {
                    // For multiple choice, allow empty array (no selections)
                    if (empty($answerChoiceIds)) {
                        continue;
                    }
                    foreach ($answerChoiceIds as $choiceId) {
                        if (empty($choiceId)) {
                            throw QuizServiceException::invalidAnswerSubmission();
                        }
                    }
                } else {
                    // For single choice, validate choice ID is not empty
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

            // Check attempt limits (0 or null means unlimited)
            if ($quiz->max_attempts !== null && $quiz->max_attempts > 0) {
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
            // Get quiz questions with pivot data to access points from quiz_questions table
            $quiz = Quiz::with('questions')->find($attempt->quiz_id);
            $quizQuestions = $quiz->questions;

            $totalPoints = $quizQuestions->sum('pivot.points');
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

            foreach ($quizQuestions as $question) {
                $correctChoiceIds = $question->answerChoices
                    ->where('is_correct', true)
                    ->pluck('id')
                    ->toArray();

                $studentChoiceIds = $answersByQuestion[$question->id] ?? [];
                $studentCorrectChoices = array_intersect($studentChoiceIds, $correctChoiceIds);
                $studentIncorrectChoices = array_diff($studentChoiceIds, $correctChoiceIds);

                // If student selected incorrect choices, no points
                if (! empty($studentIncorrectChoices)) {
                    continue;
                }

                // If student answered completely correctly
                if (
                    count($correctChoiceIds) === count($studentChoiceIds) &&
                    empty(array_diff($correctChoiceIds, $studentChoiceIds))
                ) {
                    $earnedPoints += $question->pivot->points;
                }
                // If student answered partially correctly (some correct choices but not all)
                elseif (! empty($studentCorrectChoices) && count($studentCorrectChoices) < count($correctChoiceIds)) {
                    $partialPoints = ($question->pivot->points * count($studentCorrectChoices)) / count($correctChoiceIds);
                    $earnedPoints += $partialPoints;
                }
            }

            return $earnedPoints;
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
                $studentCorrectChoices = array_intersect($studentChoiceIds, $correctChoiceIds);
                $studentIncorrectChoices = array_diff($studentChoiceIds, $correctChoiceIds);

                // If student selected incorrect choices, no points
                if (! empty($studentIncorrectChoices)) {
                    continue;
                }

                // If student answered completely correctly
                if (
                    count($correctChoiceIds) === count($studentChoiceIds) &&
                    empty(array_diff($correctChoiceIds, $studentChoiceIds))
                ) {
                    $earnedPoints += $question->points;
                }
                // If student answered partially correctly (some correct choices but not all)
                elseif (! empty($studentCorrectChoices) && count($studentCorrectChoices) < count($correctChoiceIds)) {
                    $partialPoints = ($question->points * count($studentCorrectChoices)) / count($correctChoiceIds);
                    $earnedPoints += $partialPoints;
                }
            }

            return $earnedPoints;
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
            if (! $quiz || $quiz->max_attempts === null || $quiz->max_attempts === 0) {
                return null; // null means unlimited attempts
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

            $scores = $attempts->pluck('points')->filter();

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

            return $completedAttempts->max('points');
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

    /**
     * Prepare answers in JSON format for storage
     * Format: {
     *   "question_id": number,
     *   "question_text": string,
     *   "is_multiple_response": boolean,
     *   "answer_choice_id": string|array (string for single choice, array for multiple choices)
     * }
     *
     * @param  array  $answers  Student answers in format [question_id => answer_choice_id(s)]
     * @param  Collection  $quizQuestions  Quiz questions collection
     * @return array JSON formatted answers
     */
    private function prepareAnswersJson(array $answers, Collection $quizQuestions): array
    {
        $answersJson = [];

        foreach ($answers as $questionId => $answerData) {
            // Skip null or empty answers
            if ($answerData === null || $answerData === '' || (is_array($answerData) && empty($answerData))) {
                continue;
            }

            // Get question details
            $question = $quizQuestions->where('id', $questionId)->first();
            if (! $question) {
                continue;
            }

            $questionAnswer = [
                'question_id' => $questionId,
                'question_text' => $question->question_text ?? '',
                'is_multiple_response' => $question->is_multiple_response ?? false,
            ];

            if (is_array($answerData)) {
                // Multiple choice answers - store as array
                $selectedChoices = [];
                foreach ($answerData as $choiceId) {
                    if (! empty($choiceId)) {
                        $selectedChoices[] = $choiceId;
                    }
                }
                $questionAnswer['answer_choice_id'] = $selectedChoices;
            } else {
                // Single choice answer - store as string
                if (! empty($answerData)) {
                    $questionAnswer['answer_choice_id'] = $answerData;
                }
            }

            $answersJson[] = $questionAnswer;
        }

        return $answersJson;
    }
}
