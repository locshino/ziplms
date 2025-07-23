<?php

namespace App\States\Exam;

class Completed extends Status
{
    public static string $name = 'completed';

    // SỬA LỖI: Thêm phương thức label() bắt buộc
    public static function label(): string
    {
        return 'Completed';
    }

    public function color(): string
    {
        return 'success';
    }
}
