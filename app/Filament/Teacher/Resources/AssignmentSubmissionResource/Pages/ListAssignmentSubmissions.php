<?php

namespace App\Filament\Teacher\Resources\AssignmentSubmissionResource\Pages;

use App\Filament\Teacher\Resources\AssignmentSubmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Concerns\Translatable;

class ListAssignmentSubmissions extends ListRecords
{
     use ListRecords\Concerns\Translatable;
    protected static string $resource = AssignmentSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        
        return [
            Actions\CreateAction::make(),
        ];
    }
}
