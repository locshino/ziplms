<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Actions\Action; // <-- Thêm use statement này

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('back')
                ->label('Quay về')
                ->color('gray')
                ->icon('heroicon-o-arrow-left')
                ->url(static::getResource()::getUrl('index')),

            Actions\EditAction::make()->label('Chỉnh sửa')
                ->icon('heroicon-o-pencil')
                ->color('primary'),
        ];
    }
}