<?php

namespace App\Enums;

enum ExamShowResultsType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasOptions,
        Concerns\HasKeyType;

    case IMMEDIATELY = 'immediately';
    case AFTER_EXAM_END_TIME = 'after_exam_end_time';
    case AFTER_GRADING_COMPLETED = 'after_grading_completed'; // Thêm nếu cần
    case MANUAL = 'manual';
    case SPECIFIC_DATETIME = 'specific_datetime';

    public function label(): string
    {
        return match ($this) {
            self::IMMEDIATELY => 'Ngay lập tức',
            self::AFTER_EXAM_END_TIME => 'Sau khi kỳ thi kết thúc',
            self::AFTER_GRADING_COMPLETED => 'Sau khi chấm bài hoàn tất',
            self::MANUAL => 'Thủ công bởi giáo viên',
            self::SPECIFIC_DATETIME => 'Vào một thời điểm cụ thể',
            default => 'Unknown',
        };
    }

    public static function key(): string
    {
        return 'exam-show-results-type';
    }
}
