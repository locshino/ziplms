<?php

namespace App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;

use App\Filament\Resources\UserClassMajorEnrollmentResource;
use App\Models\Role;
use Filament\Resources\Pages\CreateRecord;

class CreateUserClassMajorEnrollment extends CreateRecord
{
    protected static string $resource = UserClassMajorEnrollmentResource::class;

    protected function afterCreate(): void
    {
        $roleId = $this->data['role_id'] ?? null; 

        if ($roleId && $this->record->user) {
            $role = Role::find($roleId);
            if ($role) {
                $this->record->user->syncRoles([$role->name]);
            }
        }
    }

    public function getTitle(): string
    {
        return __('class_major_lang.Create User Class Major Enrollment');
    }
}
