<?php

namespace App\Repositories\Eloquent;

use App\Enums\QuizAttemptStatus;
use App\Models\QuizAttempt;
use App\Repositories\Interfaces\QuizAttemptRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QuizAttemptRepository extends EloquentRepository implements QuizAttemptRepositoryInterface
{
    /**
     * Get the model class name.
     */
    protected function model(): string
    {
        return QuizAttempt::class;
    }

    /**
     * Get student's attempts for a quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return Collection
     */
    public function getStudentAttempts(string $quizId, string $studentId): Collection
    {
        return $this->model->where('quiz_id', $quizId)
            ->where('student_id', $studentId)
            ->orderBy('attempt_number', 'desc')
            ->get();
    }

    /**
     * Get student's latest attempt for a quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return QuizAttempt|null
     */
    public function getLatestAttempt(string $quizId, string $studentId): ?QuizAttempt
    {
        return $this->model->where('quiz_id', $quizId)
            ->where('student_id', $studentId)
            ->orderBy('attempt_number', 'desc')
            ->first();
    }

    /**
     * Get attempt with answers.
     *
     * @param string $id
     * @return QuizAttempt|null
     */
    public function getWithAnswers(string $id): ?QuizAttempt
    {
        return $this->model->with([
            'answers.question',
            'answers.answerChoice',
            'quiz.questions.answerChoices'
        ])->find($id);
    }

    /**
     * Count student attempts for a quiz.
     *
     * @param string $quizId
     * @param string $studentId
     * @return int
     */
    public function countStudentAttempts(string $quizId, string $studentId): int
    {
        return $this->model->where('quiz_id', $quizId)
            ->where('student_id', $studentId)
            ->count();
    }

    /**
     * Get incomplete attempt for student.
     *
     * @param string $quizId
     * @param string $studentId
     * @return QuizAttempt|null
     */
    public function getIncompleteAttempt(string $quizId, string $studentId): ?QuizAttempt
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->where('student_id', $studentId)
            ->where('status', QuizAttemptStatus::IN_PROGRESS)
            ->first();
    }

    /**
     * Get completed attempts for a quiz.
     *
     * @param string $quizId
     * @return Collection
     */
    public function getCompletedAttemptsByQuiz(string $quizId): Collection
    {
        return $this->model
            ->where('quiz_id', $quizId)
            ->where('status', QuizAttemptStatus::COMPLETED)
            ->get();
    }
}