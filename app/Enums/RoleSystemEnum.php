<?php

namespace App\Enums;

enum RoleSystemEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case TEACHER = 'teacher';
    case STUDENT = 'student';

    public function label(): string
    {
        return __('role_system.'.$this->value.'.label');
    }
}
