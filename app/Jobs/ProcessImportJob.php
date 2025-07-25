<?php

namespace App\Jobs;

use Filament\Actions\Imports\Jobs\ImportCsv;

class ProcessImportJob extends ImportCsv
{
    /**
     * Get the name of the queue the job should be sent to.
     */
    public function getJobQueue(): ?string
    {
        return config('worker-queue.batch.name');
    }

    /**
     * Get the name of the connection the job should be sent to.
     */
    public function getJobConnection(): ?string
    {
        return config('worker-queue.batch.connection');
    }
}
