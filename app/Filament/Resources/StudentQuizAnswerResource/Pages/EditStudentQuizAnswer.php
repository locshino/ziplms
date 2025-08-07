<?php

namespace App\Filament\Resources\StudentQuizAnswerResource\Pages;

use App\Filament\Resources\StudentQuizAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStudentQuizAnswer extends EditRecord
{
    protected static string $resource = StudentQuizAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
