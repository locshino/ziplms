<?php

namespace App\Filament\Resources\QuestionResource\Pages;

use App\Filament\Resources\QuestionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditQuestion extends EditRecord
{
    protected static string $resource = QuestionResource::class;

    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Câu hỏi đã được cập nhật thành công.';
    }
}
