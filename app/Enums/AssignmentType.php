<?php

namespace App\Enums;

enum AssignmentType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    case Homework = 'homework';
    case Project = 'project';
    case Exam = 'exam';
    case Quiz = 'quiz';
    case Lab = 'lab';
    case FileSubmission = 'file_submission';
    case OnlineText = 'online_text';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Homework => 'Homework',
            self::Project => 'Project',
            self::Exam => 'Exam',
            self::Quiz => 'Quiz',
            self::Lab => 'Lab',
            self::FileSubmission => 'File Submission',
            self::OnlineText => 'Online Text Submission',
            self::Other => 'Other',
            default => 'Unknown Type',
        };
    }

    public static function key(): string
    {
        return 'assignment-type';
    }
}
