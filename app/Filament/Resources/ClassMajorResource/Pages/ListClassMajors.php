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
            Actions\LocaleSwitcher::make(),
            Actions\CreateAction::make(),


        ];
    }

    public function getTitle(): string
    {
        return __('class_major_lang.List of Classes Majors');
    }
}
