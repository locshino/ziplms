<?php

namespace App\Enums\Status;

use App\Enums\Concerns\StatusStyles;
use App\Enums\Contracts\HasStatusStyles;

enum AssignmentStatus: string implements HasStatusStyles
{
    use StatusStyles;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case CLOSED = 'closed';
    case ARCHIVED = 'archived';

    public function getDescription(): ?string
    {
        return __('enums_status_assignment.description.'.$this->value);
    }
}
