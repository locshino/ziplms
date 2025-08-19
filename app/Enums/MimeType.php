<?php

namespace App\Enums;

/**
 * Enum for common MIME types.
 * Provides methods to retrieve groups of MIME types like images, documents, etc.
 */
enum MimeType: string
{
    // --- Image Types ---
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case WEBP = 'image/webp';
    case SVG = 'image/svg+xml';

    // --- Document Types ---
    case PDF = 'application/pdf';
    case DOC = 'application/msword';
    case DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    case XLS = 'application/vnd.ms-excel';
    case XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    case PPT = 'application/vnd.ms-powerpoint';
    case PPTX = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';
    case TXT = 'text/plain';

    // --- Video Types ---
    case MP4 = 'video/mp4';
    case MOV = 'video/quicktime';
    case AVI = 'video/x-msvideo';
    case WMV = 'video/x-ms-wmv';
    case WEBM = 'video/webm';

    // --- Archive Types ---
    case ZIP = 'application/zip';
    case RAR = 'application/vnd.rar';

    /**
     * Get the group for a specific MIME type case.
     */
    public function group(): string
    {
        return match ($this) {
            self::JPEG, self::PNG, self::GIF, self::WEBP, self::SVG => 'image',
            self::PDF, self::DOC, self::DOCX, self::XLS, self::XLSX, self::PPT, self::PPTX, self::TXT => 'document',
            self::MP4, self::MOV, self::AVI, self::WMV, self::WEBM => 'video',
            self::ZIP, self::RAR => 'archive',
        };
    }

    /**
     * Get an array of all image MIME types.
     */
    public static function images(): array
    {
        return self::getValuesForGroup('image');
    }

    /**
     * Get an array of all document MIME types.
     */
    public static function documents(): array
    {
        return self::getValuesForGroup('document');
    }

    /**
     * Get an array of all video MIME types.
     */
    public static function videos(): array
    {
        return self::getValuesForGroup('video');
    }

    /**
     * Get an array of all archive MIME types.
     */
    public static function archives(): array
    {
        return self::getValuesForGroup('archive');
    }

    /**
     * Get an array of all defined MIME types.
     */
    public static function all(): array
    {
        return self::getValues();
    }

    /**
     * Helper to get all enum case values for a specific group.
     */
    private static function getValuesForGroup(string $group): array
    {
        return collect(self::cases())
            ->filter(fn (self $case) => $case->group() === $group)
            ->pluck('value')
            ->all();
    }

    /**
     * Helper to get all enum case values.
     */
    private static function getValues(): array
    {
        return array_column(self::cases(), 'value');
    }
}
