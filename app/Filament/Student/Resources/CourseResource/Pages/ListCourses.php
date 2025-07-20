<?php

namespace App\Filament\Student\Resources\CourseResource\Pages;

use App\Filament\Student\Resources\CourseResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Infolists\Infolist;
use Filament\Support\Infolist\Split;
use Filament\Support\Infolist\TextEntry;
use Closure;

class ListCourses extends ListRecords
{
    protected static string $resource = CourseResource::class;

    protected function getHeaderActions(): array
    {
        
        return [
            Actions\CreateAction::make(),
        ];
    }
     public function getTableRecordUrlUsing(): ?Closure
    {
        return fn ($record) => CourseResource::getUrl('view', ['record' => $record]);
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    protected function getTableContentGrid(): ?array
{
    return [
        'default' => 3,
    ];
}
     protected function getTableRecordInfolist(): ?Closure
    {
        return fn ($record) => Infolist::make([
            Split::make([
                TextEntry::make('title')
                    ->label('Tên lớp')
                    ->size(TextEntry\TextEntrySize::Large)
                    ->weight('bold'),

                TextEntry::make('code')
                    ->label('Mã lớp'),

                TextEntry::make('teacher.name')
                    ->label('Giáo viên'),

                TextEntry::make('description')
                    ->label('Mô tả')
                    ->hiddenLabel(),
            ]),
        ]);
    }

}
