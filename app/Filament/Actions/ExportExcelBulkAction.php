<?php

namespace App\Filament\Actions;

use Filament\Tables\Actions\ExportBulkAction as BaseExportBulkAction;

class ExportExcelBulkAction extends BaseExportBulkAction
{
    /**
     * This method is used to apply default configurations to the action.
     * It's the best place to set up your queue, connection, and format.
     */
    protected function setUp(): void
    {
        // Call the parent's setUp() method to apply its default configurations.
        parent::setUp();

        // Apply your custom default configurations.
        $this
            // Set a default label for the button.
            ->label('Export to Excel')

            // Set a default icon.
            ->icon('heroicon-o-arrow-down-tray')

            // Force the export format to be XLSX.
            ->formats(['xlsx'])

            // Set the queue from your config file.
            ->queue(config('worker-queue.batch.name'))

            // Set the connection from your config file.
            ->connection(config('worker-queue.batch.connection'));
    }
}
