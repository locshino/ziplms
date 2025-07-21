<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExam extends EditRecord
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\DeleteAction::make()
                ->successNotificationTitle('Bài thi đã được xóa thành công.'),
        ];
    }

    /**
     * Tùy chỉnh dữ liệu trước khi đổ vào form.
     * Cách này đảm bảo các trường translatable nhận đúng định dạng mảng
     * (ví dụ: ['vi' => '...', 'en' => '...']) mà Filament mong đợi.
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Lấy bản ghi Exam hiện tại
        $exam = $this->getRecord();

        // Lấy tất cả các bản dịch cho các trường 'title' và 'description'
        $data['title'] = $exam->getTranslations('title');
        $data['description'] = $exam->getTranslations('description');

        // Trả về mảng data đã được chuẩn hóa
        return $data;
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Bài thi đã được cập nhật thành công.';
    }
}
