<?php

namespace App\Filament\Resources\UserClassMajorEnrollmentResource\Pages;

use App\Filament\Resources\UserClassMajorEnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use App\Models\Role;
class CreateUserClassMajorEnrollment extends CreateRecord
{
    protected static string $resource = UserClassMajorEnrollmentResource::class;
        protected function afterCreate(): void
    {
        $roleId = $this->data['role_id'] ?? null; // Lấy role_id từ dữ liệu form

        if ($roleId && $this->record->user) {
            $role = Role::find($roleId);
            if ($role) {
                $this->record->user->syncRoles([$role->name]);
            }
        }
    }

}
