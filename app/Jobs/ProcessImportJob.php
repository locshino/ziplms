<?php

namespace App\Jobs;

use App\Exports\FailedRowsExport;
use App\Models\Batch;
use App\Models\User;
use App\States\Progress\Done;
use App\States\Progress\DoneWithErrors;
use App\States\Progress\Failed;
use App\States\Progress\InProgress;
use Filament\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // Allow 3 retries if the job fails
    public int $tries = 3;

    // Job times out after 10 minutes
    public int $timeout = 600;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public Batch $importBatch,
        public string $importerClass,
        public ?string $roleToAssign = null
    ) {
        $this->onQueue(config('worker-queue.batch.name'));
        $this->onConnection(config('worker-queue.batch.connection'));
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Transition state to 'processing'
        $this->importBatch->status->transitionTo(InProgress::class);

        try {
            // Instantiate the importer class provided
            $importer = new $this->importerClass($this->importBatch, $this->roleToAssign);

            // Start the import process using Laravel Excel
            Excel::import($importer, $this->importBatch->storage_path, 'filament-excel');

            $failures = $importer->getFailures();
            $notificationTitle = 'Hoàn tất nhập liệu!';
            $notificationBody = 'Quá trình nhập file "'.$this->importBatch->original_file_name.'" đã hoàn tất.';

            if (! empty($failures)) {
                // If there are failures, transition to 'completed_with_errors'
                $this->importBatch->status->transitionTo(DoneWithErrors::class);

                // Generate and store an error report
                $errorFileName = 'error_report_'.$this->importBatch->id.'.xlsx';
                $errorFilePath = 'imports/failures/'.$errorFileName;
                Excel::store(new FailedRowsExport($failures), $errorFilePath, 'filament-excel');

                $this->importBatch->update(['error_report_path' => $errorFilePath]);

                $notificationTitle = 'Nhập liệu hoàn tất với một số lỗi';
                $notificationBody .= ' Có '.count($failures).' dòng bị lỗi.';
            } else {
                // If everything is successful, transition to 'completed'
                $this->importBatch->status->transitionTo(Done::class);
            }
            // Prepare the success notification
            $notification = Notification::make()
                ->title($notificationTitle)
                ->body($notificationBody)
                ->success();
            // If an error report exists, add a download button to the notification
            if ($this->importBatch->error_report_path) {
                // NOTE: This requires `php artisan storage:link` to be executed
                // The error_report_path already contains the full relative path (e.g., 'imports/failures/error_report_id.xlsx')
                $fileInfo = pathinfo($this->importBatch->error_report_path);
                $filenameForParam = $fileInfo['dirname'].'/'.$fileInfo['filename']; // e.g., 'imports/failures/error_report_123'
                $extensionForParam = $fileInfo['extension']; // e.g., 'xlsx'

                $reportUrl = URL::temporarySignedRoute(
                    'exports.download', // Re-use the existing download route
                    now()->addHours(2), // Link valid for 2 hours
                    [
                        'filename' => $filenameForParam, // Pass the path without extension
                        'extension' => $extensionForParam, // Pass the extension separately
                        'download_as' => 'error_report_'.$this->importBatch->id.'.'.$extensionForParam, // User-friendly name
                    ]
                );

                $notification->actions([
                    NotificationAction::make('download_report')
                        ->label('Tải Báo cáo lỗi')
                        ->url($reportUrl)
                        ->markAsRead(),
                ]);
            }

            // Send notification to the user who uploaded the file
            $this->importBatch->uploader->notify($notification);
        } catch (Throwable $e) {
            // If any unexpected exception occurs, fail the job to allow for retries
            $this->fail($e);
        }
    }

    /**
     * Handle a job failure.
     * This method is called after all retries have been exhausted.
     */
    public function failed(Throwable $exception): void
    {
        // Transition state to 'failed'
        $this->importBatch->status->transitionTo(Failed::class);

        // Log the critical error for debugging
        $this->importBatch->update([
            'error_log' => [
                'critical_error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ],
        ]);

        // Send a failure notification to the uploader
        Notification::make()
            ->title('Xử lý file thất bại!')
            ->body('Đã có lỗi nghiêm trọng xảy ra khi xử lý file "'.$this->importBatch->original_file_name.'". Vui lòng liên hệ quản trị viên.')
            ->danger()
            ->sendToDatabase($this->importBatch->uploader);
    }
}
