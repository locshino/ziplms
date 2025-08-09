<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserBadgeResource\Pages;
use App\Models\UserBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UserBadgeResource extends Resource
{
    protected static ?string $model = UserBadge::class;

    protected static ?string $navigationIcon = 'heroicon-o-trophy';

    protected static ?string $navigationGroup = 'Quản lý';

    protected static ?int $navigationSort = 15;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label(__('user_badge_resource.fields.user_id'))
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Select::make('badge_id')
                    ->label(__('user_badge_resource.fields.badge_id'))
                    ->relationship('badge', 'title')
                    ->required(),
                Forms\Components\DateTimePicker::make('awarded_at')
                    ->label(__('user_badge_resource.fields.awarded_at'))
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label(__('user_badge_resource.columns.id'))
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('user_badge_resource.columns.user_name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('badge.title')
                    ->label(__('user_badge_resource.columns.badge_title'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('awarded_at')
                    ->label(__('user_badge_resource.columns.awarded_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserBadges::route('/'),
            'create' => Pages\CreateUserBadge::route('/create'),
            'view' => Pages\ViewUserBadge::route('/{record}'),
            'edit' => Pages\EditUserBadge::route('/{record}/edit'),
        ];
    }
}
