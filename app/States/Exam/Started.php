<?php

namespace App\States\Exam;

class Started extends Status
{
    public static string $name = 'started';

    public static string $langFile = 'states_exam';

    public static function label(): string
    {
        return __(static::$langFile.'.'.self::$name.'.label');
    }

    public static function color(): string
    {
        return 'info';
    }

    public static function icon(): string
    {
        return 'heroicon-o-play-circle';
    }

    public static function description(): string
    {
        return __(static::$langFile.'.'.self::$name.'.description');
    }
}
