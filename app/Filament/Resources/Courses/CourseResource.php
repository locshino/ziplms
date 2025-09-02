<?php

namespace App\Filament\Resources\Courses;

use App\Filament\RelationManagers\AuditsRelationManager;
use App\Filament\Resources\Courses\Pages\CreateCourse;
use App\Filament\Resources\Courses\Pages\EditCourse;
use App\Filament\Resources\Courses\Pages\ListCourses;
use App\Filament\Resources\Courses\RelationManagers\AssignmentsRelationManager;
use App\Filament\Resources\Courses\RelationManagers\ManagersRelationManager;
use App\Filament\Resources\Courses\RelationManagers\QuizzesRelationManager;
use App\Filament\Resources\Courses\RelationManagers\StudentsRelationManager;
use App\Filament\Resources\Courses\Schemas\CourseForm;
use App\Filament\Resources\Courses\Tables\CoursesTable;
use App\Libs\Roles\RoleHelper;
use App\Models\Course;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;

class CourseResource extends Resource
{
    protected static ?string $model = Course::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    protected static ?string $recordTitleAttribute = 'title';

    public static function getModelLabel(): string
    {
        return __('resource_course.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource_course.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource_course.resource.navigation_label');
    }

    public static function form(Schema $schema): Schema
    {
        return CourseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CoursesTable::configure($table);
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resource_course.resource.navigation_group');
    }

    public static function getRelations(): array
    {
        return [
            StudentsRelationManager::class,
            ManagersRelationManager::class,
            QuizzesRelationManager::class,
            AssignmentsRelationManager::class,
            AuditsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourses::route('/'),
            'create' => CreateCourse::route('/create'),
            'edit' => EditCourse::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        $q = parent::getEloquentQuery();

        // Handle Manager role
        if (RoleHelper::isManager()) {
            $q->whereHas('managers', fn ($query) => $query->where('users.id', Auth::id())
            );
        }

        return $q;
    }
}
