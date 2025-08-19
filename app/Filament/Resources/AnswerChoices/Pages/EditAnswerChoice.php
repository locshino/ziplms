<?php

namespace App\Filament\Resources\AnswerChoices\Pages;

use App\Filament\Resources\AnswerChoices\AnswerChoiceResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditAnswerChoice extends EditRecord
{
    protected static string $resource = AnswerChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
