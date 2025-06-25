<?php

namespace App\Filament\Resources\ExamAttemptResource\Pages;

use App\Filament\Resources\ExamAttemptResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord; // <-- Dùng ViewRecord thay vì EditRecord

class ViewExamAttempt extends ViewRecord
{
    protected static string $resource = ExamAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Bạn có thể thêm các nút hành động ở đây nếu cần, ví dụ: nút In kết quả
        ];
    }
}
