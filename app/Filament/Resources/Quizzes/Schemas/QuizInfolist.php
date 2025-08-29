<?php

namespace App\Filament\Resources\Quizzes\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuizInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('resource_quiz.infolist.entries.id')),
                TextEntry::make('title')
                    ->label(__('resource_quiz.infolist.entries.title')),
                TextEntry::make('max_attempts')
                    ->label(__('resource_quiz.infolist.entries.max_attempts'))
                    ->numeric(),
                IconEntry::make('is_single_session')
                    ->label(__('resource_quiz.infolist.entries.is_single_session'))
                    ->boolean(),
                TextEntry::make('time_limit_minutes')
                    ->label(__('resource_quiz.infolist.entries.time_limit_minutes'))
                    ->numeric(),
                TextEntry::make('status')
                    ->label(__('resource_quiz.infolist.entries.status')),
                TextEntry::make('created_at')
                    ->label(__('resource_quiz.infolist.entries.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('resource_quiz.infolist.entries.updated_at'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(__('resource_quiz.infolist.entries.deleted_at'))
                    ->dateTime(),
            ]);
    }
}
