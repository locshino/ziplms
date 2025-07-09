<?php

namespace App\States\Exam;

class Started extends Status
{
    public static string $name = 'started';

    // SỬA LỖI: Thêm phương thức label() bắt buộc
    public static function label(): string
    {
        return 'Started';
    }

    public function color(): string
    {
        return 'warning';
    }
}
