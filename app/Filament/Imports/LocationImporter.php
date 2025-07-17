<?php

namespace App\Filament\Imports;

use App\Models\Location;
use App\States\Location\LocationStatus;
use Filament\Actions\Imports\Exceptions\RowImportFailedException;
use Filament\Actions\Imports\ImportColumn;
use Filament\Facades\Filament;

class LocationImporter extends Base\Importer
{
    protected static ?string $model = Location::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name_en')
                ->label('Name (English)')
                ->rules(['required', 'max:255']),
            ImportColumn::make('name_vi')
                ->label('Name (Vietnamese)')
                ->rules(['required', 'max:255']),
            ImportColumn::make('address_en')
                ->label('Address (English)')
                ->rules(['nullable']),
            ImportColumn::make('address_vi')
                ->label('Address (Vietnamese)')
                ->rules(['nullable']),
            ImportColumn::make('latitude')
                ->label('Latitude')
                ->rules(['required', 'numeric', 'between:-90,90']),
            ImportColumn::make('longitude')
                ->label('Longitude')
                ->rules(['required', 'numeric', 'between:-180,180']),
            ImportColumn::make('status')
                ->label('Status')
                ->rules(['required', 'in:'.implode(',', array_keys(LocationStatus::getOptions()))]),
        ];
    }

    public function resolveRecord(): ?Location
    {
        return new Location;
    }

    protected function afterFill(): void
    {
        // Set translatable fields
        $this->getRecord()->setTranslations('name', [
            'en' => $this->data['name_en'],
            'vi' => $this->data['name_vi'],
        ]);

        $this->getRecord()->setTranslations('address', [
            'en' => $this->data['address_en'],
            'vi' => $this->data['address_vi'],
        ]);

        // Set location coordinates
        $this->getRecord()->locate = [
            'lat' => $this->data['latitude'],
            'lng' => $this->data['longitude'],
        ];

        // Validate status
        $status = LocationStatus::tryFrom($this->data['status']);

        if (! $status) {
            throw new RowImportFailedException('Invalid status: '.$this->data['status']);
        }

        $this->getRecord()->status = $status;
        $this->getRecord()->creator()->associate(Filament::auth()->user());
    }

    protected function afterSave(): void
    {
        // Handle any post-save actions if needed (e.g., attaching media if implemented)
    }
}
