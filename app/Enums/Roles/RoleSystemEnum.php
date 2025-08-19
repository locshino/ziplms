<?php

namespace App\Enums\Roles;

enum RoleSystemEnum: string
{
    case SUPER_ADMIN = 'super_admin';
    case ADMIN = 'admin';
    case MANAGER = 'manager';
    case TEACHER = 'teacher';
    case STUDENT = 'student';
}
