<?php

namespace App\Enums;

enum AttachmentType: string
{
    use Concerns\HasEnumValues,
        Concerns\HasKeyType,
        Concerns\HasOptions;

    // Image types
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';

    // Document types
    case PDF = 'application/pdf';
    case DOC = 'application/msword'; // Older Word
    case DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'; // Modern Word
    case XLS = 'application/vnd.ms-excel'; // Older Excel
    case XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'; // Modern Excel
    case PPT = 'application/vnd.ms-powerpoint'; // Older PowerPoint
    case PPTX = 'application/vnd.openxmlformats-officedocument.presentationml.presentation'; // Modern PowerPoint

    // Video types
    case MP4 = 'video/mp4';
    case WEBM = 'video/webm';

    public function label(): string
    {
        return match ($this) {
            self::JPEG => 'JPEG Image',
            self::PNG => 'PNG Image',
            self::GIF => 'GIF Image',
            self::PDF => 'PDF Document',
            self::DOC => 'Word Document (DOC)',
            self::DOCX => 'Word Document (DOCX)',
            self::XLS => 'Excel Spreadsheet (XLS)',
            self::XLSX => 'Excel Spreadsheet (XLSX)',
            self::PPT => 'PowerPoint Presentation (PPT)',
            self::PPTX => 'PowerPoint Presentation (PPTX)',
            self::MP4 => 'MP4 Video',
            self::WEBM => 'WebM Video',
            default => 'Unknown Type',
        };
    }

    public static function key(): string
    {
        return 'attachment-type';
    }
}
