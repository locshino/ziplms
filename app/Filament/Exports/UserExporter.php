<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id'),

            ExportColumn::make('code')
                ->label('Mã người dùng'),
            ExportColumn::make('name')
                ->label('Họ và tên'),
            ExportColumn::make('email')
                ->label('Email'),
            ExportColumn::make('phone_number')
                ->label('Số điện thoại'),
            ExportColumn::make('classesMajors.name')
                ->label('Lớp/Chuyên ngành'),
            ExportColumn::make('organizations.name')
                ->label('Cơ sở'),
            ExportColumn::make('address')
                ->label('Địa chỉ'),
            ExportColumn::make('roles.name')
                ->label('Vai trò'),
            ExportColumn::make('status')
                ->label('Trạng thái'),
            ExportColumn::make('created_at')
                ->label('Ngày tạo')
                ->state(fn (User $record): string => $record->created_at->format('d/m/Y H:i:s')),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Việc xuất dữ liệu người dùng đã hoàn tất và '.number_format($export->successful_rows).' dòng đã được xuất.';
        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' '.number_format($failedRowsCount).' dòng đã bị lỗi.';
        }

        return $body;
    }

    /**
     * Lấy tên của hàng đợi mà công việc sẽ được gửi đến.
     * Thêm vào cho giống với ScheduleExporter.php
     */
    public function getJobQueue(): ?string
    {
        return config('worker-queue.batch.name');
    }

    /**
     * Lấy tên của kết nối mà công việc sẽ được gửi đến.
     * Thêm vào cho giống với ScheduleExporter.php
     */
    public function getJobConnection(): ?string
    {
        return config('worker-queue.batch.connection');
    }

    public function getFormats(): array
    {
        return [
            ExportFormat::Csv,
            ExportFormat::Xlsx,
        ];
    }
}
