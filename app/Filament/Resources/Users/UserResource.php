<?php

namespace App\Filament\Resources\Users;

use App\Enums\System\RoleSystem;
use App\Filament\RelationManagers\AuditsRelationManager;
use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Filament\Resources\Users\Pages\ViewUser;
use App\Filament\Resources\Users\RelationManagers\AssignmentsRelationManager;
use App\Filament\Resources\Users\RelationManagers\CoursesRelationManager;
use App\Filament\Resources\Users\RelationManagers\QuizzesRelationManager;
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Schemas\UserInfolist;
use App\Filament\Resources\Users\Tables\UsersTable;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Auth;
use Tapp\FilamentAuthenticationLog\RelationManagers\AuthenticationLogsRelationManager;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUser;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getModelLabel(): string
    {
        return __('resource_user.resource.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('resource_user.resource.plural_label');
    }

    public static function getNavigationLabel(): string
    {
        return __('resource_user.resource.navigation_label');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('resource_user.resource.navigation_group');
    }

    public static function form(Schema $schema): Schema
    {
        return UserForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UsersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            CoursesRelationManager::class,
            // QuizzesRelationManager::class,
            // AssignmentsRelationManager::class,
            AuditsRelationManager::class,
            AuthenticationLogsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            // 'view' => ViewUser::route('/{record}'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            // Exclude users with the SUPER_ADMIN role
            ->whereDoesntHave('roles', function ($query) {
                $query->where('name', RoleSystem::SUPER_ADMIN->value);
            })
            // Exclude the currently authenticated user
            ->where('id', '!=', Auth::id())

            // Exclude users with higher or equal roles compared to the current user
            ->where(function ($query) {
                $currentUser = Auth::user();

                // Get the highest role of the current user
                $currentUserHighestRole = \App\Libs\Roles\RoleHelper::getHighestRole($currentUser);

                // Get all roles higher than the current user's highest role
                $higherRoles = \App\Libs\Roles\RoleHelper::getHigherRoles($currentUser);

                $query->whereDoesntHave('roles', function ($roleQuery) use ($currentUserHighestRole, $higherRoles) {
                    $rolesToExclude = array_merge(
                        (array) $higherRoles, // Ensure $higherRoles is an array
                        (array) $currentUserHighestRole // Ensure $currentUserHighestRole is an array
                    );

                    $roleQuery->whereIn('name', $rolesToExclude);
                });
            });
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
