<?php

namespace App\States\Exam;

class Active extends Status
{
    public static string $name = 'active';

    // SỬA LỖI: Thêm phương thức label() bắt buộc
    public static function label(): string
    {
        return 'Active';
    }

    public function color(): string
    {
        return 'success';
    }
}
