<?php

namespace App\Filament\Resources\ExamAttemptResource\Pages;

use App\Filament\Resources\ExamAttemptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamAttempt extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ExamAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(), //
            Actions\DeleteAction::make()
                ->successNotificationTitle(__('exam-attempt-resource.notification.delete_success')),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return __('exam-attempt-resource.notification.update_success');
    }
}
