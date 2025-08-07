<?php

namespace App\Filament\Resources\StudentQuizAnswerResource\Pages;

use App\Filament\Resources\StudentQuizAnswerResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewStudentQuizAnswer extends ViewRecord
{
    protected static string $resource = StudentQuizAnswerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
