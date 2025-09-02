<?php

namespace App\Filament\Resources\QuizAttempts;

use App\Filament\RelationManagers\AuditsRelationManager;
use App\Filament\Resources\QuizAttempts\Pages\CreateQuizAttempt;
use App\Filament\Resources\QuizAttempts\Pages\EditQuizAttempt;
use App\Filament\Resources\QuizAttempts\Pages\ListQuizAttempts;
use App\Filament\Resources\QuizAttempts\Pages\ViewQuizAttempt;
use App\Filament\Resources\QuizAttempts\Schemas\QuizAttemptForm;
use App\Filament\Resources\QuizAttempts\Schemas\QuizAttemptInfolist;
use App\Filament\Resources\QuizAttempts\Tables\QuizAttemptsTable;
use App\Models\QuizAttempt;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuizAttemptResource extends Resource
{
    protected static ?string $model = QuizAttempt::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function getModelLabel(): string
    {
        return __('resource_quiz_attempt.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource_quiz_attempt.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource_quiz_attempt.resource.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return QuizAttemptForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return QuizAttemptInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return QuizAttemptsTable::configure($table);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resource_quiz_attempt.resource.navigation_group');
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
            'index' => ListQuizAttempts::route('/'),
            'create' => CreateQuizAttempt::route('/create'),
            // 'view' => ViewQuizAttempt::route('/{record}'),
            'edit' => EditQuizAttempt::route('/{record}/edit'),
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
