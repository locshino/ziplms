<?php

namespace App\Repositories\Interfaces;

use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

interface QuestionRepositoryInterface extends EloquentRepositoryInterface
{
    /**
     * Get questions by quiz ID.
     *
     * @param string $quizId
     * @return Collection
     */
    public function getByQuizId(string $quizId): Collection;

    /**
     * Get question with answer choices.
     *
     * @param string $id
     * @return Question|null
     */
    public function getWithAnswerChoices(string $id): ?Question;

    /**
     * Get questions with answer choices by quiz ID.
     *
     * @param string $quizId
     * @return Collection
     */
    public function getWithAnswerChoicesByQuizId(string $quizId): Collection;
}