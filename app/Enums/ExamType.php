<?php

namespace App\Enums;

enum ExamType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    case Midterm = 'midterm';      // Giữa kỳ
    case Final = 'final';          // Cuối kỳ
    case Quiz = 'quiz';            // Kiểm tra nhanh
    case Assignment = 'assignment';  // Bài tập lớn

    /**
     * Cung cấp nhãn hiển thị cho người dùng.
     */
    public function label(): string
    {
        return match ($this) {
            self::Midterm => 'Kiểm tra Giữa kỳ',
            self::Final => 'Kiểm tra Cuối kỳ',
            self::Quiz => 'Kiểm tra nhanh (Quiz)',
            self::Assignment => 'Bài tập lớn tính điểm',
        };
    }

    /**
     * Cung cấp một key duy nhất cho loại enum này.
     */
    public static function key(): string
    {
        return 'exam-type';
    }
}
