<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\StudentQuizAnswer;
use App\Models\Question;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use App\Repositories\Interfaces\QuizAttemptRepositoryInterface;
use App\Services\Interfaces\QuizServiceInterface;
use App\Exceptions\Services\QuizServiceException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class QuizService extends BaseService implements QuizServiceInterface
{
    protected QuizAttemptRepositoryInterface $quizAttemptRepository;
    protected QuizCacheService $cacheService;

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
     * @param string $studentId
     * @return Collection
     */
    public function getAvailableQuizzes(string $studentId): Collection
    {
        return $this->repository->getAvailableForStudent($studentId);
    }

    /**
     * Start a new quiz attempt.
     *
     * @param string $quizId
     * @param string $studentId
     * @return QuizAttempt
     */
    public function startQuizAttempt(string $quizId, string $studentId): QuizAttempt
    {
        // Get quiz with caching
        $quiz = $this->cacheService->getQuiz($quizId);
        if (!$quiz) {
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
                    'attempt_number' => $nextAttemptNumber
                ]);

                return $attempt;
            } catch (\Exception $e) {
                Log::error('Failed to start quiz attempt', [
                    'quiz_id' => $quiz->id,
                    'student_id' => $studentId,
                    'error' => $e->getMessage()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Validate quiz availability.
     */
    private function validateQuizAvailability(Quiz $quiz): void
    {
        $now = Carbon::now();

        // Check if quiz is published
        if (!$quiz->is_published) {
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
    }

    /**
     * Validate if student can take the quiz.
     */
    private function validateStudentCanTakeQuiz(Quiz $quiz, string $studentId): void
    {
        // Check if student is enrolled in the course
        $isEnrolled = DB::table('enrollments')
            ->where('course_id', $quiz->course_id)
            ->where('student_id', $studentId)
            ->exists();
            
        if (!$isEnrolled) {
            throw QuizServiceException::quizNotActive();
        }

        // Additional business logic can be added here
        // e.g., prerequisites, time restrictions, etc.
    }

    /**
     * Continue an existing quiz attempt.
     */
    public function continueQuizAttempt(string $quizId, string $studentId): ?QuizAttempt
    {
        // Get incomplete attempt for the student
        $attempt = $this->quizAttemptRepository->getIncompleteAttempt($quizId, $studentId);
        
        if (!$attempt) {
            return null;
        }

        // Check if attempt is still valid (not expired)
        if ($attempt->quiz->time_limit) {
            $timeElapsed = Carbon::now()->diffInMinutes($attempt->started_at);
            if ($timeElapsed >= $attempt->quiz->time_limit) {
                // Auto-submit if time limit exceeded
                $this->submitQuizAttempt($attempt->id, []);
                return null;
            }
        }

        // Cache the attempt
        $this->cacheService->cacheAttempt($attempt);

        return $attempt;
    }

    /**
     * Submit quiz attempt with optimized bulk operations.
     *
     * @param string $attemptId
     * @param array $answers
     * @return QuizAttempt
     */
    public function submitQuizAttempt(string $attemptId, array $answers): QuizAttempt
    {
        return DB::transaction(function () use ($attemptId, $answers) {
            // Get attempt with caching
            $cachedAttempt = $this->cacheService->getCachedAttempt($attemptId);
            
            if ($cachedAttempt) {
                $attempt = new QuizAttempt($cachedAttempt);
            } else {
                $attempt = $this->quizAttemptRepository->findById($attemptId);
                if (!$attempt) {
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
                    if (!in_array($questionId, $questionIds)) {
                        throw QuizServiceException::invalidAnswerSubmission();
                    }
                }

                // Delete existing answers in bulk
                $existingAnswerIds = StudentQuizAnswer::where('quiz_attempt_id', $attemptId)
                    ->pluck('id')
                    ->toArray();
                    
                if (!empty($existingAnswerIds)) {
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
                if (!empty($answersToInsert)) {
                    StudentQuizAnswer::insert($answersToInsert);
                }

                // Calculate score with optimized method
                $score = $this->calculateScoreOptimized($attempt, $quizQuestions, $answersToInsert);

                // Update attempt
                $updatedAttempt = $this->quizAttemptRepository->updateById($attemptId, [
                    'score' => $score,
                    'status' => 'completed',
                    'completed_at' => Carbon::now(),
                ]);

                // Invalidate caches
                $this->cacheService->invalidateStudentAttemptsCache($attempt->quiz_id, $attempt->student_id);
                Cache::forget($this->cacheService->getAttemptCacheKey($attemptId));

                Log::info('Quiz attempt submitted successfully', [
                    'attempt_id' => $attemptId,
                    'quiz_id' => $attempt->quiz_id,
                    'student_id' => $attempt->student_id,
                    'score' => $score,
                    'answers_count' => count($answersToInsert)
                ]);

                return $updatedAttempt;
            } catch (\Exception $e) {
                Log::error('Failed to submit quiz attempt', [
                    'attempt_id' => $attemptId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Validate answers format.
     */
    private function validateAnswersFormat(array $answers): void
    {
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
    }

    /**
     * Get quiz attempt history for student.
     *
     * @param string $quizId
     * @param string $studentId
     * @return Collection
     */
    public function getAttemptHistory(string $quizId, string $studentId): Collection
    {
        $cacheKey = $this->cacheService->getStudentAttemptsCacheKey($quizId, $studentId);
        
        return Cache::remember($cacheKey, 3600, function () use ($quizId, $studentId) {
            return $this->quizAttemptRepository->getStudentAttempts($quizId, $studentId);
        });
    }

    /**
     * Get quiz attempt with answers.
     *
     * @param string $attemptId
     * @return QuizAttempt|null
     */
    public function getAttemptWithAnswers(string $attemptId): ?QuizAttempt
    {
        $cacheKey = $this->cacheService->getAttemptWithAnswersCacheKey($attemptId);
        
        return Cache::remember($cacheKey, 1800, function () use ($attemptId) {
            return $this->quizAttemptRepository->getWithAnswers($attemptId);
        });
    }

    /**
     * Check if student can take quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return bool
     */
    public function canTakeQuiz(string $quizId, string $studentId): bool
    {
        $quiz = $this->repository->findById($quizId);

        if (!$quiz) {
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
    }

    /**
     * Calculate quiz score (optimized version).
     *
     * @param QuizAttempt $attempt
     * @param Collection $questions
     * @param array $submittedAnswers
     * @return float
     */
    private function calculateScoreOptimized(QuizAttempt $attempt, Collection $questions, array $submittedAnswers): float
    {
        $totalPoints = $questions->sum('points');
        $earnedPoints = 0;

        // Group submitted answers by question
        $answersByQuestion = [];
        foreach ($submittedAnswers as $answer) {
            $questionId = $answer['question_id'];
            if (!isset($answersByQuestion[$questionId])) {
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
    }

    /**
     * Calculate quiz score (legacy method).
     *
     * @param QuizAttempt $attempt
     * @return float
     */
    public function calculateScore(QuizAttempt $attempt): float
    {
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
    }
}
