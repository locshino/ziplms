<?php

namespace App\Filament\Resources\AnswerChoices\Pages;

use App\Filament\Resources\AnswerChoices\AnswerChoiceResource;
use Asmit\ResizedColumn\HasResizableColumn;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListAnswerChoices extends ListRecords
{
    use HasResizableColumn;

    protected static string $resource = AnswerChoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
