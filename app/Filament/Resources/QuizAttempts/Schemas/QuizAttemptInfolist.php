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
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('quiz.title'),
                TextEntry::make('student.name'),
                TextEntry::make('points')
                    ->numeric(),
                TextEntry::make('start_at')
                    ->dateTime(),
                TextEntry::make('end_at')
                    ->dateTime(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
