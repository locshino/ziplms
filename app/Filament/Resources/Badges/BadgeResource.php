<?php

namespace App\Filament\Resources\Badges;

use App\Filament\Resources\Badges\Pages\CreateBadge;
use App\Filament\Resources\Badges\Pages\EditBadge;
use App\Filament\Resources\Badges\Pages\ListBadges;
use App\Filament\Resources\Badges\Pages\ViewBadge;
use App\Filament\Resources\Badges\Schemas\BadgeForm;
use App\Filament\Resources\Badges\Schemas\BadgeInfolist;
use App\Filament\Resources\Badges\Tables\BadgesTable;
use App\Models\Badge;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BadgeResource extends Resource
{
    protected static ?string $model = Badge::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('resource_badge.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource_badge.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource_badge.resource.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return BadgeForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BadgeInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BadgesTable::configure($table);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resource_badge.resource.navigation_group');
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
            'index' => ListBadges::route('/'),
            'create' => CreateBadge::route('/create'),
            'view' => ViewBadge::route('/{record}'),
            'edit' => EditBadge::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
