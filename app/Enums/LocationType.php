<?php

namespace App\Enums;

enum LocationType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    case Online = 'online';
    case OfflineRoom = 'offline_room';
    case VirtualClassroom = 'virtual_classroom';

    public function label(): string
    {
        // Bạn có thể sử dụng hàm helper __() của Laravel để hỗ trợ đa ngôn ngữ đầy đủ
        // Ví dụ: __('enums.location_type.online')
        return match ($this) {
            self::Online => 'Trực tuyến (Online)',
            self::OfflineRoom => 'Phòng học offline',
            self::VirtualClassroom => 'Lớp học ảo',
        };
    }

    public static function key(): string
    {
        return 'location-type';
    }
}
