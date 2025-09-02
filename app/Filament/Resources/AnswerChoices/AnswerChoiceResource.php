<?php

namespace App\Filament\Resources\AnswerChoices;

use App\Filament\Resources\AnswerChoices\Pages\CreateAnswerChoice;
use App\Filament\Resources\AnswerChoices\Pages\EditAnswerChoice;
use App\Filament\Resources\AnswerChoices\Pages\ListAnswerChoices;
use App\Filament\Resources\AnswerChoices\Schemas\AnswerChoiceForm;
use App\Filament\Resources\AnswerChoices\Tables\AnswerChoicesTable;
use App\Models\AnswerChoice;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Tapp\FilamentAuditing\RelationManagers\AuditsRelationManager;

class AnswerChoiceResource extends Resource
{
    protected static ?string $model = AnswerChoice::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    protected static ?string $recordTitleAttribute = 'title';

    // protected static bool $shouldRegisterNavigation = false;

    public static function getModelLabel(): string
    {
        return __('resource_answer_choice.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource_answer_choice.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource_answer_choice.resource.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return AnswerChoiceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AnswerChoicesTable::configure($table);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resource_answer_choice.resource.navigation_group');
    }

    public static function getRelations(): array
    {
        return [
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAnswerChoices::route('/'),
            // 'create' => CreateAnswerChoice::route('/create'),
            // 'edit' => EditAnswerChoice::route('/{record}/edit'),
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
