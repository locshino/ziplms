<?php

namespace App\Filament\Actions;

use App\Jobs\ProcessImportJob;
use App\Models\Batch;
use Closure;
use EightyNine\ExcelImport\ExcelImportAction as BaseExcelImportAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

/**
 * A powerful, reusable import action that extends EightyNine\ExcelImport\ExcelImportAction.
 *
 * This class provides a "hybrid" approach, leveraging the base action's robust UI
 * and form handling capabilities (validation, hooks, etc.), while delegating the
 * actual data processing to a custom, queueable job. This ensures a non-blocking,
 * reliable import experience for large files, complete with tracking via a Batch model.
 */
class ImportExcelAction extends BaseExcelImportAction
{
    /**
     * The fully qualified class name of the Maatwebsite\Excel importer.
     * This class will contain the core logic for processing each row.
     */
    protected ?string $importerClass = null;

    /**
     * An optional role name to be assigned to imported records (e.g., for users).
     */
    protected ?string $roleToAssign = null;

    /**
     * An array or closure providing extra data to be saved on the Batch model.
     * Useful for passing context like a related model's ID.
     */
    protected array|Closure $extraData = [];

    /**
     * Factory method to create a new instance of the action.
     *
     * @param  string|null  $name  The name of the action.
     */
    public static function make(?string $name = 'import'): static
    {
        $static = parent::make($name);

        /**
         * Intercept the core import logic of the parent action *before* the collection is processed.
         *
         * The `beforeImport` hook allows us to run custom logic after the form is submitted
         * but before the package attempts to parse the Excel file. This is the perfect place
         * to grab the uploaded file, create a batch record for tracking, and dispatch
         * our own background job. We then gracefully halt the default import process.
         */
        $static->beforeImport(function (array $data, $livewire, self $action) {
            /** @var UploadedFile $file */
            // The key for the file upload component from this package is 'upload'.
            $file = $data['upload'];
            $importerClass = $action->getImporterClass();

            // Ensure that a target importer class has been configured.
            if (! $importerClass) {
                Log::error('ImportAction Error: Importer class was not specified. Use the ->importer() method.');
                Notification::make()
                    ->title('Lỗi Cấu hình')
                    ->body('Importer chưa được định nghĩa cho hành động này.')
                    ->danger()
                    ->send();

                // Halt the import process
                return false;
            }

            // Step 1: Securely store the uploaded file for the background job.
            $path = $file->store('imports', 'filament-excel');

            // Step 2: Prepare the data payload for creating the Batch record.
            $batchData = array_merge([
                'uploaded_by_user_id' => Filament::auth()->id(),
                'original_file_name' => $file->getClientOriginalName(),
                'storage_path' => $path,
                'total_rows' => self::getRowCount($path),
            ], $action->getExtraData());

            // Step 3: Create the Batch record in the database.
            $batch = Batch::create($batchData);

            // Step 4: Dispatch our custom, queueable job.
            ProcessImportJob::dispatch(
                importBatch: $batch,
                importerClass: $importerClass,
                roleToAssign: $action->getRoleToAssign(),
            );

            // Step 5: Manually send a success notification to the user.
            Notification::make()
                ->title('Đã đưa vào hàng đợi!')
                ->body('File của bạn đang được xử lý trong nền. Bạn sẽ nhận được thông báo khi hoàn tất.')
                ->success()
                ->send();

            // Step 6: Return false to gracefully stop the parent action's logic
            // without throwing an unhandled exception.
            return false;
        });

        return $static;
    }

    /**
     * Set the Importer class that will handle the row-by-row processing logic.
     * This is a required configuration.
     *
     * @param  string  $class  The fully qualified class name of your Maatwebsite\Excel importer.
     */
    public function importer(string $class): static
    {
        $this->importerClass = $class;

        return $this;
    }

    /**
     * Get the configured importer class name.
     */
    public function getImporterClass(): ?string
    {
        return $this->importerClass;
    }

    /**
     * Set an optional role to be assigned to imported records.
     * Useful for importing users and assigning them to a specific role.
     *
     * @param  string  $role  The name of the role.
     */
    public function role(string $role): static
    {
        $this->roleToAssign = $role;

        return $this;
    }

    /**
     * Get the configured role name.
     */
    public function getRoleToAssign(): ?string
    {
        return $this->roleToAssign;
    }

    /**
     * Pass an array of extra data or a closure to be saved on the Batch model.
     * This allows for passing dynamic context, like a parent model ID.
     */
    public function withData(array|Closure $data): static
    {
        $this->extraData = $data;

        return $this;
    }

    /**
     * Evaluate and retrieve the extra data.
     */
    public function getExtraData(): array
    {
        return (array) $this->evaluate($this->extraData);
    }

    /**
     * A helper function to safely count the number of data rows in the uploaded file.
     *
     * @param  string  $path  The storage path of the file.
     * @return int The number of rows, excluding the header.
     */
    protected static function getRowCount(string $path): int
    {
        try {
            $rows = \Maatwebsite\Excel\Facades\Excel::toCollection(collect(), $path, 'filament-excel')->first();

            // Ensure rows is not null and subtract 1 for the header row.
            return $rows ? (max(0, $rows->count() - 1)) : 0;
        } catch (\Exception $e) {
            Log::error("Could not read row count from file {$path}: " . $e->getMessage());

            return 0; // Return 0 if the file is invalid or cannot be read.
        }
    }
}
