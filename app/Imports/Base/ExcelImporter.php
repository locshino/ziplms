<?php

namespace App\Imports\Base;

use App\Imports\Concerns\IsBigImport;
use App\Imports\Concerns\IsMediumImport;
use App\Imports\Concerns\IsSmallImport;
use App\Imports\Concerns\IsSuperBigImport;
use App\Models\Batch;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

abstract class ExcelImporter implements SkipsOnFailure, ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithValidation
{
    use SkipsFailures; // This trait will automatically collect failures for us.

    /**
     * The current import batch instance.
     * This provides context about the import job (e.g., who uploaded it, etc.).
     */
    protected Batch $importBatch;

    /**
     * An optional role to be assigned to the model after creation.
     */
    protected ?string $roleToAssign;

    /**
     * The constructor automatically receives dependencies from the ProcessImportJob.
     * Child classes do not need to override this unless they have additional dependencies.
     *
     * @param  Batch  $importBatch  The batch record tracking this import.
     * @param  string|null  $roleToAssign  An optional role to assign.
     */
    public function __construct(Batch $importBatch, ?string $roleToAssign = null)
    {
        $this->importBatch = $importBatch;
        $this->roleToAssign = $roleToAssign;
    }

    /**
     * Defines the logic to map a single row of data to an Eloquent model.
     * This method MUST be implemented by the child importer class.
     *
     * @param  array  $row  The data from a single row in the spreadsheet.
     * @return Model|null The Eloquent model instance.
     */
    abstract public function model(array $row): ?Model;

    /**
     * Defines the validation rules for each row.
     * This method MUST be implemented by the child importer class.
     *
     * @return array The array of validation rules.
     */
    abstract public function rules(): array;

    /**
     * This method is automatically called by the SkipsOnFailure concern
     * when a row fails validation. We use it to update our batch progress.
     */
    public function onFailure(Failure ...$failures)
    {
        // The SkipsFailures trait already collects failures. We just need to update our batch.
        $this->importBatch->increment('failed_imports', count($failures));
        $this->importBatch->increment('processed_rows', count($failures));
    }

    /**
     * Dynamically defines the number of models to be inserted in a single batch.
     */
    public function batchSize(): int
    {
        return $this->getPerformancePresetSize();
    }

    /**
     * Dynamically defines the number of rows to be read into memory at a time.
     */
    public function chunkSize(): int
    {
        return $this->getPerformancePresetSize();
    }

    /**
     * Determines the optimal size for chunks and batches based on the
     * concerns implemented by the child class. This avoids code duplication.
     */
    private function getPerformancePresetSize(): int
    {
        return match (true) {
            $this instanceof IsSmallImport => 100,
            $this instanceof IsMediumImport => 500,
            $this instanceof IsBigImport => 1000,
            $this instanceof IsSuperBigImport => 2000,
            default => 500, // Default and IsMediumImport
        };
    }
}
