<?php

namespace App\Enums\Status;

use App\Enums\Concerns\StatusStyles;
use App\Enums\Contracts\HasStatusStyles;

enum QuestionStatus: string implements HasStatusStyles
{
    use StatusStyles;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case REVIEW = 'review';

    public function getDescription(): ?string
    {
        return __('enums_status_question.description.' . $this->value);
    }
}
