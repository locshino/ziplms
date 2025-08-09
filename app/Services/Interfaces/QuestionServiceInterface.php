<?php

namespace App\Services\Interfaces;

use App\Models\Question;
use Illuminate\Database\Eloquent\Collection;

interface QuestionServiceInterface extends BaseServiceInterface
{
    /**
     * Create question with answer choices.
     */
    public function createWithAnswerChoices(array $data): Question;

    /**
     * Update question with answer choices.
     */
    public function updateWithAnswerChoices(string $id, array $data): Question;

    /**
     * Get questions by quiz ID.
     */
    public function getByQuizId(string $quizId): Collection;

    /**
     * Get question with answer choices.
     */
    public function getWithAnswerChoices(string $id): ?Question;
}
