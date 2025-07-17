<?php

namespace App\Filament\Teacher\Resources\AssignmentResource\Pages;

use App\Filament\Teacher\Resources\AssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAssignment extends CreateRecord
{
      use CreateRecord\Concerns\Translatable;
 
    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            // ...
        ];
    }
    protected static string $resource = AssignmentResource::class;
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        if ($data['instructions_type'] === 'file') {
            $data['instructions'] = [
                'file' => $data['instructions_file'] ?? null,
            ];
        } elseif ($data['instructions_type'] === 'url') {
            $data['instructions'] = [
                'url' => $data['instructions_url'] ?? null,
            ];
        } else {
            $data['instructions'] = [
               'text' => $data['instructions_text'] ?? null,

            ];
        }

        unset($data['instructions_file'], $data['instructions_text'], $data['instructions_type'], $data['instructions_url']);

        return $data;
    }
}
