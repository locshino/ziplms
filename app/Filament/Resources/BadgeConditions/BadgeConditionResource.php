<?php

namespace App\Filament\Resources\BadgeConditions;

use App\Filament\Resources\BadgeConditions\Pages\CreateBadgeCondition;
use App\Filament\Resources\BadgeConditions\Pages\EditBadgeCondition;
use App\Filament\Resources\BadgeConditions\Pages\ListBadgeConditions;
use App\Filament\Resources\BadgeConditions\Pages\ViewBadgeCondition;
use App\Filament\Resources\BadgeConditions\Schemas\BadgeConditionForm;
use App\Filament\Resources\BadgeConditions\Schemas\BadgeConditionInfolist;
use App\Filament\Resources\BadgeConditions\Tables\BadgeConditionsTable;
use App\Models\BadgeCondition;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BadgeConditionResource extends Resource
{
    protected static ?string $model = BadgeCondition::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('resource_badge_condition.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource_badge_condition.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource_badge_condition.resource.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return BadgeConditionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BadgeConditionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BadgeConditionsTable::configure($table);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resource_badge_condition.resource.navigation_group');
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
            'index' => ListBadgeConditions::route('/'),
            'create' => CreateBadgeCondition::route('/create'),
            'view' => ViewBadgeCondition::route('/{record}'),
            'edit' => EditBadgeCondition::route('/{record}/edit'),
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
