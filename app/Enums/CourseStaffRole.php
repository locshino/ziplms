<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CourseStaffRole: string implements HasLabel, HasColor
{
    case INSTRUCTOR = 'instructor';
    case ASSISTANT = 'assistant';
    case MENTOR = 'mentor';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::INSTRUCTOR => 'Giảng viên chính',
            self::ASSISTANT => 'Trợ giảng',
            self::MENTOR => 'Cố vấn học tập',
        };
    }


    public function getColor(): string|array|null
    {
        return match ($this) {
            self::INSTRUCTOR => 'primary', // Màu xanh dương
            self::ASSISTANT => 'success', // Màu xanh lá
            self::MENTOR => 'warning',  // Màu vàng
        };
    }
}