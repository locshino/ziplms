<?php

namespace App\Filament\Resources\BadgeConditions\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BadgeConditionInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('id')
                    ->label(__('resource_badge_condition.infolist.entries.id')),
                TextEntry::make('title')
                    ->label(__('resource_badge_condition.infolist.entries.title')),
                TextEntry::make('condition_type')
                    ->label(__('resource_badge_condition.infolist.entries.condition_type')),
                TextEntry::make('status')
                    ->label(__('resource_badge_condition.infolist.entries.status')),
                TextEntry::make('created_at')
                    ->label(__('resource_badge_condition.infolist.entries.created_at'))
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->label(__('resource_badge_condition.infolist.entries.updated_at'))
                    ->dateTime(),
                TextEntry::make('deleted_at')
                    ->label(__('resource_badge_condition.infolist.entries.deleted_at'))
                    ->dateTime(),
            ]);
    }
}
