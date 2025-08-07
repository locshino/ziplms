<?php

namespace App\Filament\Resources\AnswerChoiceResource\Pages;

use App\Filament\Resources\AnswerChoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAnswerChoice extends EditRecord
{
    protected static string $resource = AnswerChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
