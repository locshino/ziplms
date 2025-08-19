<?php

namespace App\Enums\Status;

use App\Enums\Concerns\StatusStyles;
use App\Enums\Contracts\HasStatusStyles;

enum UserStatus: string implements HasStatusStyles
{
    use StatusStyles;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case SUSPENDED = 'suspended';
    case PENDING = 'pending';

    public function getDescription(): ?string
    {
        return __('enums_status_user.description.' . $this->value);
    }
}
