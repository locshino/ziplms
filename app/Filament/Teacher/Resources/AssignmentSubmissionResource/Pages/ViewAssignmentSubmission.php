<?php

namespace App\Filament\Teacher\Resources\AssignmentSubmissionResource\Pages;


use App\Filament\Teacher\Resources\AssignmentSubmissionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Components\TextEntry;
// use Filament\SpatieMediaLibraryPlugin\Components\SpatieMediaLibraryEntry;
use Filament\Infolists\Infolist;



class ViewAssignmentSubmission extends ViewRecord
{
    protected static string $resource = AssignmentSubmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }

    public function getInfolist(string $name): Infolists\Infolist
    {
        return Infolists\Infolist::make()
        ->record($this->record)
            ->schema([
                TextEntry::make('user.name')
                    ->label('Người nộp'),

                TextEntry::make('assignment.title')
                    ->label('Tên bài tập'),

                TextEntry::make('status')
                    ->label('Trạng thái')
                    ->badge()
                    ->color(fn ($state) => $state::color())
                    ->formatStateUsing(fn ($state) => $state::label()),

                // SpatieMediaLibraryEntry::make('submissions')
                //     ->label('File đã nộp'),

                TextEntry::make('grade.grade')
                    ->label('Điểm')
                    ->placeholder('-')
                    ->formatStateUsing(fn ($state) => $state !== null ? $state : 'Chưa chấm điểm')
                    ,

                TextEntry::make('grade.feedback')
                    ->label('Phản hồi')
                    ->placeholder('-'),

                TextEntry::make('created_at')
                    ->label('Nộp lúc')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
