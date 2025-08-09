<?php

namespace App\Repositories\Eloquent;

use App\Models\Quiz;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class QuizRepository extends EloquentRepository implements QuizRepositoryInterface
{
    /**
     * Get the model class name.
     */
    protected function model(): string
    {
        return Quiz::class;
    }

    /**
     * Get quizzes by course ID.
     */
    public function getByCourseId(string $courseId): Collection
    {
        return $this->model->where('course_id', $courseId)
            ->with(['course', 'questions'])
            ->get();
    }

    /**
     * Get paginated quizzes with course information.
     */
    public function getPaginatedWithCourse(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['course'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get quiz with questions and answer choices.
     */
    public function getWithQuestionsAndChoices(string $id): ?Quiz
    {
        return $this->model->with([
            'questions' => function ($query) {
                $query->orderBy('created_at');
            },
            'questions.answerChoices' => function ($query) {
                $query->orderBy('created_at');
            },
            'course',
        ])->find($id);
    }

    /**
     * Get available quizzes for student.
     */
    public function getAvailableForStudent(string $studentId): Collection
    {
        // Get all quizzes for enrolled courses and filter by is_active in PHP
        // since is_active is a computed attribute
        $quizzes = $this->model->whereHas('course.enrollments', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
            ->with(['course'])
            ->get();

        // Filter by is_active attribute (computed)
        return $quizzes->filter(function ($quiz) {
            return $quiz->is_active;
        });
    }

    /**
     * Get quizzes by multiple course IDs.
     */
    public function getQuizzesByCourseIds(array $courseIds): Collection
    {
        return $this->model
            ->whereIn('course_id', $courseIds)
            ->with(['course'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get quizzes by course ID.
     */
    public function getQuizzesByCourseId(int $courseId): Collection
    {
        return $this->model
            ->where('course_id', $courseId)
            ->with(['course'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get quizzes for student.
     */
    public function getQuizzesByStudent(string $studentId): Collection
    {
        return $this->getAvailableForStudent($studentId);
    }

    /**
     * Get quizzes by course.
     */
    public function getQuizzesByCourse(string $courseId): Collection
    {
        return $this->getByCourseId($courseId);
    }

    /**
     * Get active quizzes.
     */
    public function getActiveQuizzes(): Collection
    {
        return $this->model->get()->filter(function ($quiz) {
            return $quiz->is_active;
        });
    }

    /**
     * Get quiz with questions.
     */
    public function getQuizWithQuestions(string $quizId): ?Quiz
    {
        return $this->model->with(['questions', 'course'])->find($quizId);
    }

    /**
     * Get quiz with attempts.
     */
    public function getQuizWithAttempts(string $quizId): ?Quiz
    {
        return $this->model->with(['attempts', 'course'])->find($quizId);
    }

    /**
     * Get upcoming quizzes.
     */
    public function getUpcomingQuizzes(): Collection
    {
        return $this->model->where('start_at', '>', Carbon::now())
            ->with(['course'])
            ->orderBy('start_at')
            ->get();
    }

    /**
     * Get paginated quizzes with course.
     */
    public function getPaginatedQuizzesWithCourse(int $perPage = 15): LengthAwarePaginator
    {
        return $this->getPaginatedWithCourse($perPage);
    }

    /**
     * Get question with answer choices.
     */
    public function getQuestionWithAnswerChoices(string $questionId): ?\App\Models\Question
    {
        return \App\Models\Question::with(['answerChoices'])->find($questionId);
    }

    /**
     * Get questions with answer choices by quiz ID.
     */
    public function getQuestionsWithAnswerChoicesByQuizId(string $quizId): Collection
    {
        return \App\Models\Question::where('quiz_id', $quizId)
            ->with(['answerChoices'])
            ->orderBy('created_at')
            ->get();
    }
}
