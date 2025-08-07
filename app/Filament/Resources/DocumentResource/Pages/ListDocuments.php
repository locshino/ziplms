<?php

namespace App\Filament\Resources\DocumentResource\Pages;

use App\Filament\Resources\DocumentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDocuments extends ListRecords
{
    protected static string $resource = DocumentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Tạo tài liệu mới'),
        ];
    }

    // Override để hiển thị thông báo khi không có dữ liệu
    protected function getTableEmptyStateHeading(): ?string
    {
        return 'Chưa có tài liệu nào';
    }

    protected function getTableEmptyStateDescription(): ?string
    {
        return 'Tạo tài liệu đầu tiên của bạn bằng cách nhấp vào nút "Tạo tài liệu mới".';
    }
}