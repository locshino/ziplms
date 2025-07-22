<?php

namespace App\Filament\Exports;

use App\Models\Lecture;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class LectureExporter extends Exporter
{
    protected static ?string $model = Lecture::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('title')->label('Tiêu đề bài giảng'),
            ExportColumn::make('description')->label('Mô tả'),
            ExportColumn::make('course.name')->label('Môn học'),
            ExportColumn::make('duration_estimate')->label('Thời lượng dự kiến'),
            ExportColumn::make('lecture_order')->label('Thứ tự bài giảng'),
            ExportColumn::make('status')->label('Trạng thái'),
            ExportColumn::make('created_at')->label('Ngày tạo'),
            ExportColumn::make('updated_at')->label('Ngày cập nhật'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your lecture export has completed and '.number_format($export->successful_rows).' '.str('row')->plural($export->successful_rows).' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to export.';
        }

        return $body;
    }
}
