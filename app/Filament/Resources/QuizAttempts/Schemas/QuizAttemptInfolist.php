<?php

namespace App\Filament\Resources\QuizAttempts\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuizAttemptInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('quiz.title')
                    ->label('Quiz'),
                TextEntry::make('student.name')
                    ->label('Student'),
                TextEntry::make('points')
                    ->numeric()
                    ->label('Points'),
                TextEntry::make('status')
                    ->label('Status'),
                TextEntry::make('start_at')
                    ->dateTime()
                    ->label('Start At'),
                TextEntry::make('end_at')
                    ->dateTime()
                    ->label('End At'),
                // Hiển thị phần Answers bằng Livewire component (nếu hỗ trợ trong Infolist)
                // Nếu không hỗ trợ, có thể dùng TextEntry hoặc custom component
                \Filament\Schemas\Components\Section::make('Answers')
                    ->columnSpanFull()
                    ->collapsed()
                    ->lazy()
                    ->schema([
                        \Filament\Schemas\Components\Livewire::make(\App\Livewire\ShowQuizAnswers::class)
                            ->key(fn ($record) => $record?->id),
                    ]),
            ]);
    }
}
