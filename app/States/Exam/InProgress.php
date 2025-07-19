<?php

// File: app/States/Exam/InProgress.php
// ------------------------------------
// Trạng thái: Người dùng đang trong quá trình làm bài thi.

namespace App\States\Exam;

class InProgress extends Status
{
    public static string $name = 'in_progress';

    public static function label(): string
    {
        return 'Đang diễn ra';
    }

    public function color(): string
    {
        // Màu vàng để chỉ sự hoạt động
        return 'warning';
    }
}
