<?php

namespace App\Filament\Resources\QuizAttempts\Pages;

use App\Filament\Resources\QuizAttempts\QuizAttemptResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewQuizAttempt extends ViewRecord
{
    protected static string $resource = QuizAttemptResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
