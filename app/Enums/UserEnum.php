<?php

namespace App\Enums;

// Giả sử bạn có trait này, giữ nguyên nó
// use Concerns\HasEnumValues; 

enum UserEnum: string
{
    // use Concerns\HasEnumValues;

    case Admin = 'admin';
    case Manager = 'manager';
    case Teacher = 'teacher';
    case Student = 'student';
    case Dev = 'dev';

    public function color(): string
    {
        return match ($this) {
            self::Admin => 'danger',
            self::Manager => 'primary',
            self::Teacher => 'success',
            self::Student => 'warning',
            self::Dev => 'info',
        };
    }
}