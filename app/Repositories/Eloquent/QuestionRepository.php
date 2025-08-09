<?php

namespace App\Repositories\Eloquent;

use App\Models\Question;
use App\Repositories\Interfaces\QuestionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class QuestionRepository extends EloquentRepository implements QuestionRepositoryInterface
{
    /**
     * Get the model class name.
     */
    protected function model(): string
    {
        return Question::class;
    }

    /**
     * Get questions by quiz ID.
     */
    public function getByQuizId(string $quizId): Collection
    {
        return $this->model->where('quiz_id', $quizId)
            ->orderBy('created_at')
            ->get();
    }

    /**
     * Get question with answer choices.
     */
    public function getWithAnswerChoices(string $id): ?Question
    {
        return $this->model->with(['answerChoices' => function ($query) {
            $query->orderBy('created_at');
        }])->find($id);
    }

    /**
     * Get questions with answer choices by quiz ID.
     */
    public function getWithAnswerChoicesByQuizId(string $quizId): Collection
    {
        return $this->model->where('quiz_id', $quizId)
            ->with(['answerChoices' => function ($query) {
                $query->orderBy('created_at');
            }])
            ->orderBy('created_at')
            ->get();
    }
}
