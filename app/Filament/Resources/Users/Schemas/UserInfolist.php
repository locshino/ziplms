<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('resource_user.infolist.sections.account_information'))
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                Group::make([
                                    SpatieMediaLibraryImageEntry::make('avatar')
                                        ->collection('avatar')
                                        ->label(__('resource_user.infolist.entries.avatar'))
                                        ->circular()
                                        ->size(120)
                                        ->defaultImageUrl(url('/images/default-avatar.svg')),
                                    TextEntry::make('name')
                                        ->label(__('resource_user.infolist.entries.name'))
                                        ->weight('bold'),
                                    TextEntry::make('email')
                                        ->label(__('resource_user.infolist.entries.email'))
                                        ->icon('heroicon-o-envelope')
                                        ->copyable(),
                                ])
                                    ->columnSpan(1),

                                Group::make([

                                    TextEntry::make('status')
                                        ->label(__('resource_user.infolist.entries.status'))
                                        ->badge(),
                                    TextEntry::make('roles.name')
                                        ->label(__('resource_user.infolist.entries.roles'))
                                        ->badge()
                                        ->separator(', ')
                                        ->placeholder(__('resource_user.infolist.entries.no_roles_assigned')),
                                    TextEntry::make('id')
                                        ->label(__('resource_user.infolist.entries.user_id'))
                                        ->copyable()
                                        ->icon('heroicon-o-hashtag'),
                                    TextEntry::make('email_verified_at')
                                        ->label(__('resource_user.infolist.entries.email_verified'))
                                        ->dateTime()
                                        ->placeholder(__('resource_user.infolist.entries.not_verified'))
                                        ->icon('heroicon-o-check-circle'),
                                ])
                                    ->columnSpan(1),
                            ]),
                    ])
                    ->collapsible()
                    ->columnSpanFull(),

                Section::make(__('resource_user.infolist.sections.timestamps'))
                    ->schema([
                        Grid::make(3)
                            ->schema([
                                TextEntry::make('created_at')
                                    ->label(__('resource_user.infolist.entries.created'))
                                    ->dateTime()
                                    ->icon('heroicon-o-plus-circle'),
                                TextEntry::make('updated_at')
                                    ->label(__('resource_user.infolist.entries.last_updated'))
                                    ->dateTime()
                                    ->icon('heroicon-o-pencil-square'),
                                TextEntry::make('deleted_at')
                                    ->label(__('resource_user.infolist.entries.deleted'))
                                    ->dateTime()
                                    ->placeholder(__('resource_user.infolist.entries.not_deleted'))
                                    ->icon('heroicon-o-trash'),
                            ]),
                    ])
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull(),
            ]);
    }
}
