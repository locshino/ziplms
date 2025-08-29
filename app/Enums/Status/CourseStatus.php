<?php

namespace App\Enums\Status;

use App\Enums\Concerns\StatusStyles;
use App\Enums\Contracts\HasStatusStyles;

enum CourseStatus: string implements HasStatusStyles
{
    use StatusStyles;

    case DRAFT = 'draft';
    case PUBLISHED = 'published';
    case ARCHIVED = 'archived';
    case SUSPENDED = 'suspended';

    public function getDescription(): ?string
    {
        return __('enums_status_course.description.'.$this->value);
    }
}
