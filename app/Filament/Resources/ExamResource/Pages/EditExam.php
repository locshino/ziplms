<?php

namespace App\Filament\Resources\ExamResource\Pages;

use App\Filament\Resources\ExamResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExam extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make()
                ->successNotificationTitle(__('exam-resource.notification.delete_success')),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('exam-resource.notification.update_success');
    }
}
