<?php

// File: app/States/Exam/Inactive.php

namespace App\States\Exam;

class Inactive extends Status
{
    public static string $name = 'inactive';

    public static string $langFile = 'states_exam';

    public static function label(): string
    {
        return __(static::$langFile.'.'.self::$name.'.label');
    }

    public static function color(): string
    {
        return 'gray';
    }

    public static function icon(): string
    {
        return 'heroicon-o-x-circle';
    }

    public static function description(): string
    {
        return __(static::$langFile.'.'.self::$name.'.description');
    }
}
