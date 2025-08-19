<?php

namespace App\Filament\Resources\RoleResource\Pages;

use App\Filament\Resources\RoleResource;
use App\Services\Interfaces\RoleServiceInterface;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRole extends EditRecord
{
    protected static string $resource = RoleResource::class;

    /**
     * Role service instance.
     */
    protected RoleServiceInterface $roleService;

    /**
     * Processed form data.
     */
    protected array $processedData = [];

    /**
     * Boot method to inject dependencies.
     */
    public function boot(): void
    {
        $this->roleService = app(RoleServiceInterface::class);
    }

    protected function getActions(): array
    {
        $actions = [];

        // Only show delete action for non-system roles
        if (! $this->record->is_system) {
            $actions[] = Actions\DeleteAction::make()
                ->requiresConfirmation()
                ->modalHeading('Delete Role')
                ->modalDescription('Are you sure you want to delete this role? This action cannot be undone.')
                ->modalSubmitActionLabel('Yes, delete it');
        }

        return $actions;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        try {
            /** @var \App\Models\Role $role */
            $role = $this->record;
            $this->processedData = $this->roleService->processFormDataBeforeSave($role, $data);

            return $this->processedData;
        } catch (\App\Exceptions\Services\RoleServiceException $e) {
            Notification::make()
                ->title('Cannot edit system role')
                ->body('System roles cannot be modified.')
                ->danger()
                ->send();

            $this->halt();

            return $data; // Return original data if exception occurs
        }
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\Role $role */
        $role = $this->record;
        $this->roleService->syncPermissionsAfterSave($role, $this->processedData);
    }
}
