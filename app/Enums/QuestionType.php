<?php

namespace App\Enums;

enum QuestionType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    case SingleChoice = 'mcq_single_choice';
    case MultipleChoice = 'mcq_multiple_choice';
    case TrueFalse = 'true_false';
    case Essay = 'essay';
    case ShortAnswer = 'short_answer';
    case FillBlank = 'fill_blank';

    public function label(): string
    {
        return match ($this) {
            self::SingleChoice => 'Single Choice',
            self::MultipleChoice => 'Multiple Choice',
            self::TrueFalse => 'True/False',
            self::Essay => 'Essay',
            self::ShortAnswer => 'Short Answer',
            self::FillBlank => 'Fill in the Blank',
            default => 'Unknown Type',
        };
    }

    public static function key(): string
    {
        return 'question-type';
    }
}
