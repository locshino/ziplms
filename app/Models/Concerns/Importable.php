<?php

namespace App\Models\Concerns;

use App\Models\Batch;

trait Importable
{
    /**
     * The import batch context, temporarily attached to the model.
     * This property will not be persisted to the database.
     *
     * @var \App\Models\Batch|null
     */
    public ?Batch $importBatch = null;

    /**
     * The role to assign after creation, temporarily attached to the model.
     *
     * @var string|null
     */
    public ?string $roleToAssignOnCreate = null;

    /**
     * Boot the trait.
     * This method is automatically called when the model using this trait is booted.
     */
    public static function bootImportable(): void
    {
        // Register a 'created' model event listener.
        // This will fire immediately after a model is successfully saved to the database.
        static::created(function ($model) {
            // Check if the model was created in the context of an import batch.
            if ($model->importBatch instanceof Batch) {
                $model->importBatch->increment('successful_imports');
                $model->importBatch->increment('processed_rows');
            }

            // Check if a role needs to be assigned and if the model has the 'assignRole' method (from Spatie Permission).
            if ($model->roleToAssignOnCreate && method_exists($model, 'assignRole')) {
                $model->assignRole($model->roleToAssignOnCreate);
            }
        });
    }
}
