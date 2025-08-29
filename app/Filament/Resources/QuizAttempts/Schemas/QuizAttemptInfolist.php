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
                    ->label(__('resource_quiz_attempt.infolist.entries.quiz.title')),
                TextEntry::make('student.name')
                    ->label(__('resource_quiz_attempt.infolist.entries.student.name')),
                TextEntry::make('points')
                    ->numeric()
                    ->label(__('resource_quiz_attempt.infolist.entries.points')),
                TextEntry::make('status')
                    ->label(__('resource_quiz_attempt.infolist.entries.status')),
                TextEntry::make('start_at')
                    ->dateTime()
                    ->label(__('resource_quiz_attempt.infolist.entries.start_at')),
                TextEntry::make('end_at')
                    ->dateTime()
                    ->label(__('resource_quiz_attempt.infolist.entries.end_at')),
                // Hiển thị phần Answers bằng Livewire component (nếu hỗ trợ trong Infolist)
                // Nếu không hỗ trợ, có thể dùng TextEntry hoặc custom component
                \Filament\Schemas\Components\Section::make(__('resource_quiz_attempt.infolist.entries.answers'))
                    ->columnSpanFull()
                    ->collapsed()
                    ->lazy()
                    ->schema([
                        \Filament\Schemas\Components\Livewire::make(\App\Livewire\ShowQuizAnswers::class)
                            ->key(fn($record) => $record?->id),
                    ]),
            ]);
    }
}
