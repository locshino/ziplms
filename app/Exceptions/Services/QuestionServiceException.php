<?php

namespace App\Exceptions\Services;

use Exception;

class QuestionServiceException extends Exception
{
    /**
     * Exception when question is not found.
     */
    public static function questionNotFound(string $questionId): self
    {
        return new self("Câu hỏi với ID {$questionId} không tồn tại.");
    }

    /**
     * Exception when no correct answer is provided.
     */
    public static function noCorrectAnswer(): self
    {
        return new self('Phải có ít nhất một đáp án đúng.');
    }

    /**
     * Exception when single choice question has multiple correct answers.
     */
    public static function multipleCorrectAnswersForSingleChoice(): self
    {
        return new self('Câu hỏi một lựa chọn chỉ được có một đáp án đúng.');
    }

    /**
     * Exception when insufficient answer choices are provided.
     */
    public static function insufficientAnswerChoices(int $minimum = 2): self
    {
        return new self("Câu hỏi phải có ít nhất {$minimum} lựa chọn đáp án.");
    }

    /**
     * Exception when question points are invalid.
     */
    public static function invalidPoints(): self
    {
        return new self('Điểm số câu hỏi phải lớn hơn 0.');
    }

    /**
     * Exception when question title is too long.
     */
    public static function titleTooLong(int $maxLength = 1000): self
    {
        return new self("Tiêu đề câu hỏi không được vượt quá {$maxLength} ký tự.");
    }

    /**
     * Exception when answer choice title is too long.
     */
    public static function answerChoiceTitleTooLong(int $maxLength = 500): self
    {
        return new self("Nội dung đáp án không được vượt quá {$maxLength} ký tự.");
    }

    /**
     * Exception when trying to delete a question that has been answered.
     */
    public static function questionHasAnswers(): self
    {
        return new self('Không thể xóa câu hỏi đã có học sinh trả lời.');
    }

    /**
     * Exception when quiz is not found for the question.
     */
    public static function quizNotFound(): self
    {
        return new self('Quiz không tồn tại.');
    }

    /**
     * Exception when trying to modify question in an active quiz.
     */
    public static function quizIsActive(): self
    {
        return new self('Không thể chỉnh sửa câu hỏi trong quiz đang diễn ra.');
    }
}
