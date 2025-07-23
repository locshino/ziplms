<?php

namespace App\States\Exam;

class Cancelled extends Status
{
    public static string $name = 'cancelled';

    public static function label(): string
    {
        return 'Cancelled';
    }

    public function color(): string
    {
        // Màu đỏ để chỉ sự nguy hiểm hoặc hủy bỏ
        return 'danger';
    }
}
