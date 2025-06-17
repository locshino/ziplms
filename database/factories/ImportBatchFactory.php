<?php

namespace Database\Factories;

use App\Models\ImportBatch;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ImportBatch>
 */
class ImportBatchFactory extends Factory
{
    protected $model = ImportBatch::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalRows = fake()->numberBetween(10, 100);
        $successfulImports = fake()->numberBetween(0, $totalRows);
        $failedImports = $totalRows - $successfulImports;

        $error_log = null;
        $isHasErrors = $failedImports > 0;
        if ($isHasErrors) {
            $error_log = json_encode(['errors' => [fake()->sentence]]);
        }

        return [
            'organization_id' => Organization::factory(),
            'uploaded_by_user_id' => User::factory(),
            'original_file_name' => fake()->word.'.csv',
            'storage_path' => 'imports/'.fake()->uuid.'.csv',
            'total_rows' => $totalRows,
            'processed_rows' => $totalRows,
            'successful_imports' => $successfulImports,
            'failed_imports' => $failedImports,
            'error_log' => $error_log,
        ];
    }
}
