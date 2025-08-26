<?php

namespace App\Enums\System;

use Filament\Support\Contracts\HasLabel;

enum RoleSystem: string implements HasLabel
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case TEACHER = 'teacher';
    case STUDENT = 'student';

    public function getLabel(): string
    {
        return match ($this) {
            self::SUPER_ADMIN => __('role_system.super_admin'),
            self::ADMIN => __('role_system.admin'),
            self::MANAGER => __('role_system.manager'),
            self::TEACHER => __('role_system.teacher'),
            self::STUDENT => __('role_system.student'),
        };
    }
}
