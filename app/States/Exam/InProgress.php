<?php

// File: app/States/Exam/InProgress.php

namespace App\States\Exam;

class InProgress extends Status
{
    public static string $name = 'in_progress';

    public static string $langFile = 'states_exam';

    public static function label(): string
    {
        return __(static::$langFile.'.'.self::$name.'.label');
    }

    public static function color(): string
    {
        return 'warning';
    }

    public static function icon(): string
    {
        return 'heroicon-o-clock';
    }

    public static function description(): string
    {
        return __(static::$langFile.'.'.self::$name.'.description');
    }
}
