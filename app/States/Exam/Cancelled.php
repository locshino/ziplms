<?php

// File: app/States/Exam/Cancelled.php

namespace App\States\Exam;

class Cancelled extends Status
{
    public static string $name = 'cancelled';

    public static string $langFile = 'states_exam';

    public static function label(): string
    {
        return __(static::$langFile.'.'.self::$name.'.label');
    }

    public static function color(): string
    {
        return 'danger';
    }

    public static function icon(): string
    {
        return 'heroicon-o-minus-circle';
    }

    public static function description(): string
    {
        return __(static::$langFile.'.'.self::$name.'.description');
    }
}
