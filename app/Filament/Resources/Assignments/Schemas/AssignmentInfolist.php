<?php

namespace App\Filament\Resources\Assignments\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class AssignmentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('resource_assignment.infolist.entries.id')),
                TextEntry::make('title')
                    ->label(__('resource_assignment.infolist.entries.title')),
                TextEntry::make('max_points')
                    ->label(__('resource_assignment.infolist.entries.max_points'))
                    ->numeric(),
                TextEntry::make('max_attempts')
                    ->label(__('resource_assignment.infolist.entries.max_attempts'))
                    ->numeric(),
                TextEntry::make('status')
                    ->label(__('resource_assignment.infolist.entries.status')),
                TextEntry::make('created_at')
                    ->label(__('resource_assignment.infolist.entries.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('resource_assignment.infolist.entries.updated_at'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(__('resource_assignment.infolist.entries.deleted_at'))
                    ->dateTime(),
            ]);
    }
}
