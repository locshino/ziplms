<?php

namespace App\Filament\Resources\ClassMajorResource\Pages;

use App\Filament\Resources\ClassMajorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassMajor extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected static string $resource = ClassMajorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),

            
        ];
    }

    public function getTitle(): string
    {
        return __('class_major_lang.Edit Classes Major');
    }
    
}
