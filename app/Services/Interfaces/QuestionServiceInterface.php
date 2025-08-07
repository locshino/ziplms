<?php

namespace App\Services\Interfaces;

use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

interface QuestionServiceInterface extends BaseServiceInterface
{
    /**
     * Create question with answer choices.
     *
     * @param array $data
     * @return Question
     */
    public function createWithAnswerChoices(array $data): Question;

    /**
     * Update question with answer choices.
     *
     * @param string $id
     * @param array $data
     * @return Question
     */
    public function updateWithAnswerChoices(string $id, array $data): Question;

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
}