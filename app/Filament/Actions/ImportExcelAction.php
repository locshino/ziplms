<?php

namespace App\Filament\Actions;

use App\Jobs\ProcessImportJob;
use App\Models\Batch;
use Closure;
use EightyNine\ExcelImport\ExcelImportAction as BaseExcelImportAction;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

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

        // We leverage the base action's form and modal setup.
        // The real magic happens by intercepting the process with the `processCollectionUsing()` method.

        /**
         * Intercept the core import logic of the parent action after the collection is processed.
         *
         * The `processCollectionUsing` method allows us to define a custom closure that runs
         * after the library has successfully parsed the Excel file into a Laravel Collection.
         * This gives us access to both the parsed data (which we will ignore) and the
         * original form data, allowing us to take full control of the process.
         */
        $static->processCollectionUsing(function (Collection $collection, array $data, self $action) {
            /** @var UploadedFile $file */
            $file = $data['file'];
            $importerClass = $action->getImporterClass();

            // Ensure that a target importer class has been configured.
            if (! $importerClass) {
                Log::error('ImportAction Error: Importer class was not specified. Use the ->importer() method.');
                Notification::make()
                    ->title('Lỗi Cấu hình')
                    ->body('Importer chưa được định nghĩa cho hành động này.')
                    ->danger()
                    ->send();

                return; // Halt execution.
            }

            // Step 1: Securely store the uploaded file in a persistent location for the background job.
            $path = $file->store('imports', 'local');

            // Step 2: Prepare the data payload for creating the Batch record.
            // This includes default data and any extra data passed via withData().
            $batchData = array_merge([
                'uploaded_by_user_id' => Filament::auth()->id(), // Use Filament's auth helper for context clarity.
                'original_file_name' => $file->getClientOriginalName(),
                'storage_path' => $path,
                'total_rows' => self::getRowCount($path),
            ], $action->getExtraData());

            // Step 3: Create the Batch record in the database to track the import's lifecycle.
            $batch = Batch::create($batchData);

            // Step 4: Dispatch our custom, queueable job to handle the heavy lifting in the background.
            // This ensures the UI remains responsive for the user.
            ProcessImportJob::dispatch(
                importBatch: $batch,
                importerClass: $importerClass,
                roleToAssign: $action->getRoleToAssign(),
            )
                ->onConnection(config('worker-queue.batch.connection', 'redis'))
                ->onQueue(config('worker-queue.batch.name', 'ziplms_batches'));

            // We ignore the `$collection` parameter because our custom Job will handle
            // processing directly from the stored file. This ensures a consistent and
            // robust background execution flow.

            // Step 5: Provide immediate feedback to the user, confirming the process has started.
            Notification::make()
                ->title('Đã đưa vào hàng đợi!')
                ->body('File của bạn đang được xử lý trong nền. Bạn sẽ nhận được thông báo khi hoàn tất.')
                ->success()
                ->send();

            // By not returning anything, we prevent the parent
            // BaseExcelImportAction from executing its own logic. We've successfully
            // taken over the process.
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
        // Cast to array to ensure the return type hint is always satisfied,
        // even if the evaluated closure returns a different type.
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
            $rows = Excel::toCollection(collect(), storage_path('app/'.$path))->first();

            // Ensure rows is not null and subtract 1 for the header row.
            return $rows ? (max(0, $rows->count() - 1)) : 0;
        } catch (\Exception $e) {
            Log::error("Could not read row count from file {$path}: ".$e->getMessage());

            return 0; // Return 0 if the file is invalid or cannot be read.
        }
    }
}
