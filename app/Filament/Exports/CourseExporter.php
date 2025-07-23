<?php

namespace App\Filament\Exports;

use App\Models\Course;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CourseExporter extends Exporter
{
    protected static ?string $model = Course::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('code')
                ->label('Mã Môn Học'),
            ExportColumn::make('name')
                ->label('Tên Môn Học (Tiếng Việt)')
                ->state(fn (Course $record): string => $record->getTranslation('name', 'vi') ?? ''),
            ExportColumn::make('description')
                ->label('Mô Tả (Tiếng Việt)')
                ->state(fn (Course $record): string => $record->getTranslation('description', 'vi') ?? ''),
            ExportColumn::make('organization.name')
                ->label('Tên Tổ Chức'),
            ExportColumn::make('parent.code')
                ->label('Mã Môn Học Cha'),
            ExportColumn::make('start_date')
                ->label('Ngày Bắt Đầu'),
            ExportColumn::make('end_date')
                ->label('Ngày Kết Thúc'),
            ExportColumn::make('status')
                ->label('Trạng Thái')
                ->state(fn (Course $record): string => $record->status->label()),
        ];
    }

    // Tùy chỉnh thông báo khi xuất file thành công
    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Xuất thành công '.number_format($export->successful_rows).' '.str('dòng')->plural($export->successful_rows).'.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' '.str('dòng')->plural($failedRowsCount).' đã bị lỗi.';
        }

        return $body;
    }
}
