<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExam extends EditRecord
{
    protected static string $resource = ExamResource::class;

    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\DeleteAction::make()
                ->successNotificationTitle('Bài thi đã được xóa thành công.'),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Bài thi đã được cập nhật thành công.';
    }
}
