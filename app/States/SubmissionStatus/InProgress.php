<?php

namespace App\States\SubmissionStatus;

class InProgress extends SubmissionStatus
{
    public static $name = 'in_progress';
    public static function label(): string
    {
        return 'Đã lưu trữ';
    }

    public static function color(): string
    {
        return 'gray';
    }
}
