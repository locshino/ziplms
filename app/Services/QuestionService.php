<?php

namespace App\Services;

use App\Models\AnswerChoice;
use App\Models\Question;
use App\Models\Quiz;
use App\Repositories\Interfaces\QuestionRepositoryInterface;
use App\Services\Interfaces\QuestionServiceInterface;
use App\Exceptions\Services\QuestionServiceException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class QuestionService extends BaseService implements QuestionServiceInterface
{
    protected QuestionRepositoryInterface $questionRepository;
    protected QuizCacheService $cacheService;

    public function __construct(
        QuestionRepositoryInterface $questionRepository,
        QuizCacheService $cacheService
    ) {
        parent::__construct($questionRepository);
        $this->questionRepository = $questionRepository;
        $this->cacheService = $cacheService;
    }

    /**
     * Validate question data before creation/update.
     */
    private function validateQuestionData(array $data): void
    {
        // Validate title
        if (empty($data['title']) || strlen(trim($data['title'])) === 0) {
            throw QuestionServiceException::questionNotFound('Title is required');
        }

        if (strlen($data['title']) > 1000) {
            throw QuestionServiceException::titleTooLong();
        }

        // Validate points
        if (!isset($data['points']) || $data['points'] <= 0) {
            throw QuestionServiceException::invalidPoints();
        }

        // Validate answer choices
        if (!isset($data['answer_choices']) || !is_array($data['answer_choices'])) {
            throw QuestionServiceException::insufficientAnswerChoices();
        }

        if (count($data['answer_choices']) < 2) {
            throw QuestionServiceException::insufficientAnswerChoices();
        }

        // Validate answer choice content
        foreach ($data['answer_choices'] as $choice) {
            if (empty($choice['title']) || strlen(trim($choice['title'])) === 0) {
                throw QuestionServiceException::answerChoiceTitleTooLong(0);
            }
            if (strlen($choice['title']) > 500) {
                throw QuestionServiceException::answerChoiceTitleTooLong();
            }
        }

        // Validate correct answers
        $correctAnswers = array_filter($data['answer_choices'], fn($choice) => $choice['is_correct'] ?? false);
        
        if (empty($correctAnswers)) {
            throw QuestionServiceException::noCorrectAnswer();
        }

        // Note: Single choice validation is handled at form level to provide better UX
    }

    /**
     * Check if quiz is active and prevent modifications.
     */
    private function validateQuizNotActive(string $quizId): void
    {
        $quiz = $this->cacheService->getQuiz($quizId);
        
        if (!$quiz) {
            throw QuestionServiceException::quizNotFound();
        }

        $now = Carbon::now();
        if ($quiz->start_at && $quiz->end_at && 
            $now->between($quiz->start_at, $quiz->end_at)) {
            throw QuestionServiceException::quizIsActive();
        }
    }

    /**
     * Create question with answer choices.
     *
     * @param array $data
     * @return Question
     * @throws QuestionServiceException
     */
    public function createWithAnswerChoices(array $data): Question
    {
        // Validate input data
        $this->validateQuestionData($data);
        
        // Check if quiz is not active
        $this->validateQuizNotActive($data['quiz_id']);

        return DB::transaction(function () use ($data) {
            try {
                // Create the question
                $question = $this->questionRepository->create([
                    'quiz_id' => $data['quiz_id'],
                    'title' => trim($data['title']),
                    'points' => $data['points'],
                    'is_multiple_response' => $data['is_multiple_response'] ?? false,
                ]);

                // Prepare answer choices for bulk insert
                $answerChoices = [];
                foreach ($data['answer_choices'] as $choiceData) {
                    $answerChoices[] = [
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'question_id' => $question->id,
                        'title' => trim($choiceData['title']),
                        'is_correct' => $choiceData['is_correct'] ?? false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert answer choices
                AnswerChoice::insert($answerChoices);

                // Invalidate cache
                $this->cacheService->invalidateQuizCache($data['quiz_id']);

                Log::info('Question created successfully', [
                    'question_id' => $question->id,
                    'quiz_id' => $data['quiz_id'],
                    'answer_choices_count' => count($answerChoices)
                ]);

                return $question->load('answerChoices');
            } catch (\Exception $e) {
                Log::error('Failed to create question', [
                    'quiz_id' => $data['quiz_id'],
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Update question with answer choices.
     *
     * @param string $id
     * @param array $data
     * @return Question
     * @throws QuestionServiceException
     */
    public function updateWithAnswerChoices(string $id, array $data): Question
    {
        // Get existing question
        $existingQuestion = $this->questionRepository->findById($id);
        if (!$existingQuestion) {
            throw QuestionServiceException::questionNotFound($id);
        }

        // Validate input data
        $this->validateQuestionData($data);
        
        // Check if quiz is not active
        $this->validateQuizNotActive($existingQuestion->quiz_id);

        // Check if question has been answered (prevent modification)
        $hasAnswers = DB::table('student_quiz_answers')
            ->where('question_id', $id)
            ->exists();
            
        if ($hasAnswers) {
            throw QuestionServiceException::questionHasAnswers();
        }

        return DB::transaction(function () use ($id, $data, $existingQuestion) {
            try {
                // Update the question
                $this->questionRepository->updateById($id, [
                    'title' => trim($data['title']),
                    'points' => $data['points'],
                    'is_multiple_response' => $data['is_multiple_response'] ?? false,
                ]);
                
                // Get updated question
                $question = $this->questionRepository->findById($id);

                // Get existing answer choice IDs for efficient deletion
                $existingChoiceIds = AnswerChoice::where('question_id', $id)
                    ->pluck('id')
                    ->toArray();

                // Delete existing answer choices in bulk
                if (!empty($existingChoiceIds)) {
                    AnswerChoice::whereIn('id', $existingChoiceIds)->delete();
                }

                // Prepare new answer choices for bulk insert
                $answerChoices = [];
                foreach ($data['answer_choices'] as $choiceData) {
                    $answerChoices[] = [
                        'id' => (string) \Illuminate\Support\Str::uuid(),
                        'question_id' => $question->id,
                        'title' => trim($choiceData['title']),
                        'is_correct' => $choiceData['is_correct'] ?? false,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk insert new answer choices
                AnswerChoice::insert($answerChoices);

                // Invalidate cache
                $this->cacheService->invalidateQuizCache($existingQuestion->quiz_id);

                Log::info('Question updated successfully', [
                    'question_id' => $id,
                    'quiz_id' => $existingQuestion->quiz_id,
                    'deleted_choices' => count($existingChoiceIds),
                    'new_choices' => count($answerChoices)
                ]);

                return $question->load('answerChoices');
            } catch (\Exception $e) {
                Log::error('Failed to update question', [
                    'question_id' => $id,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                throw $e;
            }
        });
    }

    /**
     * Get questions by quiz ID.
     *
     * @param string $quizId
     * @return Collection
     */
    public function getByQuizId(string $quizId): Collection
    {
        // Try to get from cache first
        $cachedQuestions = $this->cacheService->getQuizQuestions($quizId);
        
        if (!empty($cachedQuestions)) {
            return collect($cachedQuestions)->map(function ($questionData) {
                $question = new Question($questionData);
                $question->answerChoices = collect($questionData['answer_choices'] ?? [])
                    ->map(fn($choice) => new AnswerChoice($choice));
                return $question;
            });
        }

        return $this->questionRepository->getByQuizId($quizId);
    }

    /**
     * Get question with answer choices.
     *
     * @param string $id
     * @return Question|null
     */
    public function getWithAnswerChoices(string $id): ?Question
    {
        return $this->questionRepository->getWithAnswerChoices($id);
    }

    /**
     * Bulk create questions with answer choices.
     *
     * @param  string  $quizId
     * @param  array  $questionsData
     * @return array
     */
    public function bulkCreateQuestions(string $quizId, array $questionsData): array
    {
        // Validate quiz is not active
        $this->validateQuizNotActive($quizId);

        $createdQuestions = [];
        $errors = [];

        return DB::transaction(function () use ($quizId, $questionsData, &$createdQuestions, &$errors) {
            foreach ($questionsData as $index => $questionData) {
                try {
                    $questionData['quiz_id'] = $quizId;
                    $this->validateQuestionData($questionData);
                    
                    $question = $this->createWithAnswerChoices($questionData);
                    $createdQuestions[] = $question;
                } catch (\Exception $e) {
                    $errors[] = [
                        'index' => $index,
                        'error' => $e->getMessage(),
                        'data' => $questionData
                    ];
                }
            }

            if (!empty($errors)) {
                Log::warning('Bulk question creation had errors', [
                    'quiz_id' => $quizId,
                    'total' => count($questionsData),
                    'successful' => count($createdQuestions),
                    'errors' => $errors
                ]);
            }

            return [
                'successful' => $createdQuestions,
                'errors' => $errors,
                'total' => count($questionsData),
                'success_count' => count($createdQuestions),
                'error_count' => count($errors)
            ];
        });
    }

    /**
     * Bulk delete questions.
     *
     * @param  array  $questionIds
     * @return array
     */
    public function bulkDeleteQuestions(array $questionIds): array
    {
        $deletedCount = 0;
        $errors = [];
        $quizIds = [];

        return DB::transaction(function () use ($questionIds, &$deletedCount, &$errors, &$quizIds) {
            foreach ($questionIds as $questionId) {
                try {
                    $question = $this->questionRepository->findById($questionId);
                    
                    if (!$question) {
                        $errors[] = [
                            'question_id' => $questionId,
                            'error' => 'Question not found'
                        ];
                        continue;
                    }

                    // Check if question has been answered
                    $hasAnswers = DB::table('student_quiz_answers')
                        ->where('question_id', $questionId)
                        ->exists();
                        
                    if ($hasAnswers) {
                        $errors[] = [
                            'question_id' => $questionId,
                            'error' => 'Question has been answered and cannot be deleted'
                        ];
                        continue;
                    }

                    // Check if quiz is not active
                    $this->validateQuizNotActive($question->quiz_id);
                    
                    $quizIds[] = $question->quiz_id;

                    // Delete answer choices first
                    AnswerChoice::where('question_id', $questionId)->delete();
                    
                    // Delete question
                    $this->questionRepository->deleteById($questionId);
                    $deletedCount++;
                    
                } catch (\Exception $e) {
                    $errors[] = [
                        'question_id' => $questionId,
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Invalidate cache for affected quizzes
            foreach (array_unique($quizIds) as $quizId) {
                $this->cacheService->invalidateQuizCache($quizId);
            }

            Log::info('Bulk question deletion completed', [
                'total' => count($questionIds),
                'deleted' => $deletedCount,
                'errors' => count($errors)
            ]);

            return [
                'deleted_count' => $deletedCount,
                'errors' => $errors,
                'total' => count($questionIds),
                'error_count' => count($errors)
            ];
        });
    }

    /**
     * Get question statistics for a quiz.
     *
     * @param  string  $quizId
     * @return array
     */
    public function getQuestionStatistics(string $quizId): array
    {
        return Cache::remember(
            "quiz_question_stats:{$quizId}",
            1800, // 30 minutes
            function () use ($quizId) {
                $questions = Question::where('quiz_id', $quizId)
                    ->with('answerChoices')
                    ->get();

                $totalQuestions = $questions->count();
                $totalPoints = $questions->sum('points');
                $multipleChoiceCount = $questions->where('is_multiple_response', true)->count();
                $singleChoiceCount = $totalQuestions - $multipleChoiceCount;
                $avgChoicesPerQuestion = $questions->avg(function ($question) {
                    return $question->answerChoices->count();
                });

                return [
                    'total_questions' => $totalQuestions,
                    'total_points' => $totalPoints,
                    'average_points_per_question' => $totalQuestions > 0 ? round($totalPoints / $totalQuestions, 2) : 0,
                    'single_choice_count' => $singleChoiceCount,
                    'multiple_choice_count' => $multipleChoiceCount,
                    'average_choices_per_question' => round($avgChoicesPerQuestion, 2),
                    'updated_at' => Carbon::now()->toISOString()
                ];
            }
        );
    }

    /**
     * Duplicate questions from one quiz to another.
     *
     * @param  string  $sourceQuizId
     * @param  string  $targetQuizId
     * @return array
     */
    public function duplicateQuestions(string $sourceQuizId, string $targetQuizId): array
    {
        // Validate target quiz is not active
        $this->validateQuizNotActive($targetQuizId);

        $sourceQuestions = $this->getByQuizId($sourceQuizId);
        $duplicatedQuestions = [];

        return DB::transaction(function () use ($sourceQuestions, $targetQuizId, &$duplicatedQuestions) {
            foreach ($sourceQuestions as $sourceQuestion) {
                $questionData = [
                    'quiz_id' => $targetQuizId,
                    'title' => $sourceQuestion->title,
                    'points' => $sourceQuestion->points,
                    'is_multiple_response' => $sourceQuestion->is_multiple_response,
                    'answer_choices' => $sourceQuestion->answerChoices->map(function ($choice) {
                        return [
                            'title' => $choice->title,
                            'is_correct' => $choice->is_correct
                        ];
                    })->toArray()
                ];

                $duplicatedQuestion = $this->createWithAnswerChoices($questionData);
                $duplicatedQuestions[] = $duplicatedQuestion;
            }

            Log::info('Questions duplicated successfully', [
                'source_quiz_id' => $sourceQuizId,
                'target_quiz_id' => $targetQuizId,
                'duplicated_count' => count($duplicatedQuestions)
            ]);

            return [
                'duplicated_questions' => $duplicatedQuestions,
                'count' => count($duplicatedQuestions)
            ];
        });
    }
}
