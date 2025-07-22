<?php

namespace App\States\Exam;

class Inactive extends Status
{
    public static string $name = 'inactive';

    // SỬA LỖI: Thêm phương thức label() bắt buộc
    public static function label(): string
    {
        return 'Inactive';
    }

    public function color(): string
    {
        return 'danger';
    }
}
