<?php

namespace App\Filament\Resources\ExamAttemptResource\Pages;

use App\Filament\Resources\ExamAttemptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExamAttempt extends EditRecord
{
    protected static string $resource = ExamAttemptResource::class;

    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
