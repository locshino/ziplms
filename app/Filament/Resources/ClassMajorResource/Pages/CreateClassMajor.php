<?php

namespace App\Filament\Resources\ClassMajorResource\Pages;

use App\Filament\Resources\ClassMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClassMajor extends CreateRecord
{
    use CreateRecord\Concerns\Translatable;

    protected static string $resource = ClassMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),

        ];
    }

    public function getTitle(): string
    {
        return __('filament.Create Classes Major');
    }
}
