<?php

namespace App\Filament\Resources\ClassMajorResource\Pages;

use App\Filament\Resources\ClassMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListClassMajors extends ListRecords
{
    use ListRecords\Concerns\Translatable;

    protected static string $resource = ClassMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Actions\LocaleSwitcher::make(),
        ];
    }

    public function getTitle(): string
    {
        return __('filament.List of Classes Majors');
    }
}
