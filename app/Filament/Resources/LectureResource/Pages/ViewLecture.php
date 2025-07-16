<?php

namespace App\Filament\Resources\LectureResource\Pages;

use App\Filament\Resources\LectureResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLecture extends ViewRecord
{
    protected static string $resource = LectureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Nút "Sửa" đã có
            Actions\EditAction::make(),

            Actions\Action::make('cancel')
                ->label('Cancel')
                ->url($this->getResource()::getUrl('index')) 
                ->color('gray'),
        ];
    }
}