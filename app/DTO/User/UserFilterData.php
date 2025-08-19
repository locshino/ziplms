<?php

namespace App\DTO\User;

use App\DTO\Concerns\InteractsWithArray;
use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;

class UserFilterData
{
    use InteractsWithArray;

    public function __construct(
        public ?UserStatus $status = null,
        public ?string $keyword = null,
        public ?RoleSystem $role = null
    ) {}
}
