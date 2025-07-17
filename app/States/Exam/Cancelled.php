<?php

namespace App\States\Exam;

use App\States\Exam\Status;

class Cancelled extends Status
{
    public static string $name = 'cancelled';

    public static function label(): string
    {
        return 'Đã hủy';
    }

    public function color(): string
    {
        // Màu đỏ để chỉ sự nguy hiểm hoặc hủy bỏ
        return 'danger';
    }
}
