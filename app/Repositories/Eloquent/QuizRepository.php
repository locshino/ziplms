<?php

namespace App\Repositories\Eloquent;

use App\Models\Quiz;
use App\Repositories\Interfaces\QuizRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Carbon\Carbon;

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
     *
     * @param string $courseId
     * @return Collection
     */
    public function getByCourseId(string $courseId): Collection
    {
        return $this->model->where('course_id', $courseId)
            ->with(['course', 'questions'])
            ->get();
    }

    /**
     * Get paginated quizzes with course information.
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getPaginatedWithCourse(int $perPage = 15): LengthAwarePaginator
    {
        return $this->model->with(['course'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get quiz with questions and answer choices.
     *
     * @param string $id
     * @return Quiz|null
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
            'course'
        ])->find($id);
    }

    /**
     * Get available quizzes for student.
     *
     * @param string $studentId
     * @return Collection
     */
    public function getAvailableForStudent(string $studentId): Collection
    {
        $now = Carbon::now();
        
        return $this->model->whereHas('course.enrollments', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
        ->where(function ($query) use ($now) {
            $query->whereNull('start_at')
                ->orWhere('start_at', '<=', $now);
        })
        ->where(function ($query) use ($now) {
            $query->whereNull('end_at')
                ->orWhere('end_at', '>=', $now);
        })
        ->with(['course'])
        ->get();
    }
}