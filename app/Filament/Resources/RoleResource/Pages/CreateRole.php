<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Enums\Permissions\PermissionContextEnum;
use App\Enums\Permissions\PermissionNounEnum;
use App\Enums\Permissions\PermissionVerbEnum;
use App\Filament\Resources\RoleResource;
use App\Libs\Permissions\PermissionHelper;
use App\Models\Permission;
use BezhanSalleh\FilamentShield\Support\Utils;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    public Collection $permissions;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Handle new permissions creation
        $this->handleNewPermissions($data);

        $this->permissions = collect($data)
            ->filter(function ($permission, $key) {
                return ! in_array($key, ['name', 'guard_name', 'select_all', 'is_system', 'new_permissions', 'existing_custom_permissions', Utils::getTenantModelForeignKey()]);
            })
            ->values()
            ->flatten()
            ->unique();

        // Add existing custom permissions if selected
        if (isset($data['existing_custom_permissions']) && is_array($data['existing_custom_permissions'])) {
            $this->permissions = $this->permissions->merge($data['existing_custom_permissions']);
        }

        // Ensure new roles are always non-system roles
        $data['is_system'] = false;

        if (Arr::has($data, Utils::getTenantModelForeignKey())) {
            return Arr::only($data, ['name', 'guard_name', 'is_system', Utils::getTenantModelForeignKey()]);
        }

        return Arr::only($data, ['name', 'guard_name', 'is_system']);
    }

    protected function afterCreate(): void
    {
        $permissionModels = collect();
        $this->permissions->each(function ($permission) use ($permissionModels) {
            $permissionModels->push(Utils::getPermissionModel()::firstOrCreate([
                /** @phpstan-ignore-next-line */
                'name' => $permission,
                'guard_name' => $this->data['guard_name'],
            ]));
        });

        $this->record->syncPermissions($permissionModels);
    }

    /**
     * Handle creation of new permissions from the permission management tab.
     */
    protected function handleNewPermissions(array $data): void
    {
        if (! isset($data['new_permissions']) || ! is_array($data['new_permissions'])) {
            return;
        }

        foreach ($data['new_permissions'] as $newPermission) {
            if (! isset($newPermission['verb'], $newPermission['noun'], $newPermission['context'])) {
                continue;
            }

            try {
                $builder = PermissionHelper::make();

                // Set verb
                $verbEnum = PermissionVerbEnum::from($newPermission['verb']);
                $builder->verb($verbEnum);

                // Set noun
                $nounEnum = PermissionNounEnum::from($newPermission['noun']);
                $builder->noun($nounEnum);

                // Set context
                $contextEnum = PermissionContextEnum::from($newPermission['context']);
                $builder->context($contextEnum);

                // Add attribute value if needed
                if (in_array($newPermission['context'], [PermissionContextEnum::ID->value, PermissionContextEnum::TAG->value]) && ! empty($newPermission['attribute_value'])) {
                    $builder->withAttribute($newPermission['attribute_value']);
                }

                $permissionName = $builder->build();
                $guardName = $newPermission['guard_name'] ?? 'web';

                // Create the permission if it doesn't exist
                Permission::firstOrCreate([
                    'name' => $permissionName,
                    'guard_name' => $guardName,
                ], [
                    'is_system' => false,
                ]);

                // Add to permissions collection for role assignment
                $this->permissions = $this->permissions ?? collect();
                $this->permissions->push($permissionName);

            } catch (\Exception $e) {
                // Log error or handle gracefully
                continue;
            }
        }
    }
}
