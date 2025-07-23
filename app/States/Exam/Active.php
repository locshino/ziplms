<?php

// File: app/States/Exam/Active.php

namespace App\States\Exam;

class Active extends Status
{
    public static string $name = 'active';

    public static string $langFile = 'states_exam';

    public static function label(): string
    {
        return __(static::$langFile.'.'.self::$name.'.label');
    }

    public static function color(): string
    {
        return 'success';
    }

    public static function icon(): string
    {
        return 'heroicon-o-check-circle';
    }

    public static function description(): string
    {
        return __(static::$langFile.'.'.self::$name.'.description');
    }
}
