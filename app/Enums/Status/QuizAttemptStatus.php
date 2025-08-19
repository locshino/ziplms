<?php

namespace App\Enums\Status;

use App\Enums\Concerns\StatusStyles;
use App\Enums\Contracts\HasStatusStyles;

enum QuizAttemptStatus: string implements HasStatusStyles
{
    use StatusStyles;

    case STARTED = 'started';
    case IN_PROGRESS = 'in_progress';
    case COMPLETED = 'completed';
    case ABANDONED = 'abandoned';
    case GRADED = 'graded';

    public function getDescription(): ?string
    {
        return __('enums_status_quiz_attempt.description.' . $this->value);
    }
}
