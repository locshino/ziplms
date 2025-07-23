<?php

namespace App\Filament\Resources\AssignmentResource\Pages;

use App\Filament\Resources\AssignmentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAssignment extends EditRecord
{
    use EditRecord\Concerns\Translatable;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),

        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
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

    protected static string $resource = AssignmentResource::class;
}
