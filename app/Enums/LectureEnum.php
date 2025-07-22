<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;

enum LectureEnum: string implements HasColor, HasIcon, HasLabel
{
    case ACTIVE = 'active';
    case IN_PROGRESS = 'in_progress';
    case PENDING = 'pending';
    case ARCHIVED = 'archived';
    case CANCELLED = 'cancelled';
    case INACTIVE = 'inactive';
    case COMPLETED = 'completed';
    case POSTPONED = 'postponed';

    public function getLabel(): ?string
    {
        return match ($this) {
            self::ACTIVE => 'Hoạt động',
            self::IN_PROGRESS => 'Đang diễn ra',
            self::PENDING => 'Đang chờ',
            self::ARCHIVED => 'Đã lưu trữ',
            self::CANCELLED => 'Đã hủy',
            self::INACTIVE => 'Không hoạt động',
            self::COMPLETED => 'Đã hoàn thành',
            self::POSTPONED => 'Bị hoãn',
        };
    }

    public function getColor(): string|array|null
    {
        return match ($this) {
            self::ACTIVE, self::COMPLETED => 'success',
            self::IN_PROGRESS, self::PENDING => 'warning',
            self::ARCHIVED => 'info',
            self::CANCELLED, self::INACTIVE => 'danger',
            self::POSTPONED => 'gray',
        };
    }

    public function getIcon(): ?string
    {
        return match ($this) {
            self::ACTIVE, self::COMPLETED => 'heroicon-o-check-circle',
            self::IN_PROGRESS => 'heroicon-o-play-circle',
            self::PENDING => 'heroicon-o-clock',
            self::ARCHIVED => 'heroicon-o-archive-box',
            self::CANCELLED, self::INACTIVE => 'heroicon-o-x-circle',
            self::POSTPONED => 'heroicon-o-pause-circle',
        };
    }
}
