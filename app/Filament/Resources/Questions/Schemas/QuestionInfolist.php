<?php

namespace App\Filament\Resources\Questions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class QuestionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('resource_question.infolist.entries.id')),
                TextEntry::make('status')
                    ->label(__('resource_question.infolist.entries.status')),
                TextEntry::make('created_at')
                    ->label(__('resource_question.infolist.entries.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('resource_question.infolist.entries.updated_at'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(__('resource_question.infolist.entries.deleted_at'))
                    ->dateTime(),
            ]);
    }
}
