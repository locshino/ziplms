<?php

namespace App\Enums\Status;

use App\Enums\Concerns\StatusStyles;
use App\Enums\Contracts\HasStatusStyles;

enum BadgeConditionStatus: string implements HasStatusStyles
{
    use StatusStyles;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case ARCHIVED = 'archived';

    public function getDescription(): ?string
    {
        return __('enums_status_badge_condition.description.'.$this->value);
    }
}
