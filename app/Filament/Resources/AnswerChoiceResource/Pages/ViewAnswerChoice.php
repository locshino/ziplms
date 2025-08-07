<?php

namespace App\Filament\Resources\AnswerChoiceResource\Pages;

use App\Filament\Resources\AnswerChoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAnswerChoice extends ViewRecord
{
    protected static string $resource = AnswerChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
