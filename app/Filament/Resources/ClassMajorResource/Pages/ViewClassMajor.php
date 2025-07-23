<?php

namespace App\Filament\Resources\ClassMajorResource\Pages;

use App\Filament\Resources\ClassMajorResource;
use Filament\Actions;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;

class ViewClassMajor extends ViewRecord
{
    protected static string $resource = ClassMajorResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
            Placeholder::make('class_major_name')
                ->label('Tên đơn vị')
                ->content(fn () => $this->record->name),
            Placeholder::make('total_people')
                ->label('Tổng số người trong đơn vị')
                ->content(fn () => $this->record->enrollments->count().' người'),
            Placeholder::make('organization')->label('Tổ chức')
                ->content(fn () => $this->record->organization->name),
            Placeholder::make('code')->label('Mã đơn vị')
                ->content(fn () => $this->record->code),
            Placeholder::make('parent')->label('Đơn vị Cha')
                ->content(fn () => $this->record->parent ? $this->record->parent->name : 'Không có'),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [

            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),

            // FilamentRecordNav\Actions\PreviousRecordAction::make(),
            // FilamentRecordNav\Actions\NextRecordAction::make(),
        ];
    }
}
