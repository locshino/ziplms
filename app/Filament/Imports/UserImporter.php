<?php

namespace App\Filament\Imports;

use App\Enums\Status\UserStatus;
use App\Enums\System\RoleSystem;
use App\Libs\Roles\RoleHelper;
use App\Models\User;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;

class UserImporter extends Importer
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->example('John Doe')
                ->requiredMapping()
                ->rules(['required']),
            ImportColumn::make('email')
                ->example('john.doe@example.com')
                ->requiredMapping()
                ->rules(['required', 'email', 'unique:users,email']),
            ImportColumn::make('password')
                ->example('password123')
                ->requiredMapping()
                ->rules(['required', 'min:8']),
        ];
    }

    public function resolveRecord(): User
    {
        $user = new User;

        if ($this->options['updateExisting'] ?? false) {
            $existingUser = User::whereEmail($this->data['email'])->first();

            if ($existingUser) {
                $currentUserId = Auth::id();
                $isRoleMatch = RoleHelper::compareUserRoles($existingUser) === 1;
                $isSuperAdmin = RoleHelper::isSuperAdmin($existingUser);
                $isCurrentUser = $existingUser->id === $currentUserId;

                // Only reuse existing user if role matches and not super admin
                $user = (! $isRoleMatch || $isSuperAdmin || $isCurrentUser) ? new User : $existingUser;
            } else {
                $user = new User;
            }
        }

        $user->status = $this->options['default_status'] ?? UserStatus::ACTIVE->value;

        // // Assign role after the user is saved
        $user->saving(function ($model) {
            $model->assignRole($this->options['default_role'] ?? RoleSystem::STUDENT->value);
        });

        return $user;
    }

    public static function getOptionsFormComponents(): array
    {
        return [
            Checkbox::make('updateExisting')
                ->label('Update existing records'),
            Select::make('default_status')
                ->options(UserStatus::class)
                ->required()
                ->helperText('The status to assign to the imported users.'),
            Select::make('default_role')
                ->options(RoleHelper::getBaseSystemRoles())
                ->required()
                ->helperText('The role to assign to the imported users.'),
        ];
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your user import has completed and '.Number::format($import->successful_rows).' '.str('row')->plural($import->successful_rows).' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' '.Number::format($failedRowsCount).' '.str('row')->plural($failedRowsCount).' failed to import.';
        }

        return $body;
    }
}
