<?php

namespace App\Enums;

enum RoleEnum: string
{
    use Concerns\HasEnumValues;

    case Admin = 'admin';
    case Manager = 'manager';
    case Teacher = 'teacher';
    case Student = 'student';

    public function color(): string
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Manager => 'warning',
            self::Teacher => 'info',
            self::Student => 'success',
        };
    }
}