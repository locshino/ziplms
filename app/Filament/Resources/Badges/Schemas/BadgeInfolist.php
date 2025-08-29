<?php

namespace App\Filament\Resources\Badges\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BadgeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('resource_badge.infolist.entries.id')),
                TextEntry::make('title')
                    ->label(__('resource_badge.infolist.entries.title')),
                TextEntry::make('slug')
                    ->label(__('resource_badge.infolist.entries.slug')),
                TextEntry::make('status')
                    ->label(__('resource_badge.infolist.entries.status')),
                TextEntry::make('created_at')
                    ->label(__('resource_badge.infolist.entries.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('resource_badge.infolist.entries.updated_at'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(__('resource_badge.infolist.entries.deleted_at'))
                    ->dateTime(),
            ]);
    }
}
