<?php

namespace App\Enums\Status;

use App\Enums\Concerns\StatusStyles;
use App\Enums\Contracts\HasStatusStyles;

enum SubmissionStatus: string implements HasStatusStyles
{
    use StatusStyles;

    case DRAFT = 'draft';
    case SUBMITTED = 'submitted';
    case GRADED = 'graded';
    case RETURNED = 'returned';
    case LATE = 'late';

    public function getDescription(): ?string
    {
        return __('enums_status_submission.description.'.$this->value);
    }
}
