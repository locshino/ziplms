<?php

namespace App\Filament\Resources\Submissions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class SubmissionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label('ID'),
                TextEntry::make('assignment.title'),
                TextEntry::make('student.name'),
                TextEntry::make('status'),
                TextEntry::make('submitted_at')
                    ->dateTime(),
                TextEntry::make('graded_by'),
                TextEntry::make('points')
                    ->numeric(),
                TextEntry::make('graded_at')
                    ->dateTime(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->dateTime(),
            ]);
    }
}
