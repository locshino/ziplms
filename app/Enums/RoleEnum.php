<?php

namespace App\Enums;

enum RoleEnum: string
{
    use Concerns\HasEnumValues;

    case Admin = 'admin';
    case Manager = 'manager';
    case Teacher = 'teacher';
    case Student = 'student';
}
