<?php

namespace App\Filament\Resources\ExamAttemptResource\Pages;

use App\Filament\Resources\ExamAttemptResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use ListRecords\Concerns\Translatable;

class EditExamAttempt extends EditRecord
{
    protected static string $resource = ExamAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
