<?php

namespace App\Jobs;

use App\Exports\FailedRowsExport;
use App\Models\Batch;
use App\States\Progress\Done;
use App\States\Progress\DoneWithErrors;
use App\States\Progress\Failed;
use App\States\Progress\InProgress;
use App\Support\FileDownloadHelper;
use Filament\Actions\Action as NotificationAction;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ProcessImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;

    public function __construct(
        public Batch $importBatch,
        public string $importerClass,
        public ?string $roleToAssign = null
    ) {
        // You can set queue connection/name here if needed, or in the dispatch call.
    }

    public function handle(): void
    {
        $this->importBatch->status->transitionTo(InProgress::class);

        try {
            $importer = new $this->importerClass($this->importBatch, $this->roleToAssign);

            // Start the import process. We use 'local' disk because that's where the Action stored it.
            Excel::import($importer, $this->importBatch->storage_path, 'local');

            // --- THIS IS THE FIX ---
            // Call the correct method `failures()` provided by the SkipsFailures trait.
            $failures = $importer->failures();

            $notificationTitle = 'Hoàn tất nhập liệu!';
            $notificationBody = 'Quá trình nhập file "' . $this->importBatch->original_file_name . '" đã hoàn tất.';

            if (count($failures) > 0) {
                $this->importBatch->status->transitionTo(DoneWithErrors::class);

                // Generate and store an error report
                $errorFileName = 'error_report_' . $this->importBatch->id . '.xlsx';
                $errorFilePath = 'imports/failures/' . $errorFileName;
                Excel::store(new FailedRowsExport($failures), $errorFilePath, 'local');

                $this->importBatch->update(['error_report_path' => $errorFilePath]);

                $notificationTitle = 'Nhập liệu hoàn tất với một số lỗi';
                $notificationBody .= ' Có ' . count($failures) . ' dòng bị lỗi.';
            } else {
                $this->importBatch->status->transitionTo(Done::class);
            }

            // Prepare notification
            $notification = Notification::make()
                ->title($notificationTitle)
                ->body($notificationBody)
                ->success();

            // Add download button if an error report exists
            if ($this->importBatch->error_report_path) {
                $reportUrl = FileDownloadHelper::generateWafFriendlySignedUrl(
                    filePath: $this->importBatch->error_report_path,
                    isErrorReport: true
                );

                $notification->actions([
                    NotificationAction::make('download_report')
                        ->label('Tải Báo cáo lỗi')
                        ->url($reportUrl)
                        ->markAsRead(),
                ]);
            }

            // Send notification to the uploader
            $this->importBatch->uploader->notify($notification->toDatabase());
        } catch (Throwable $e) {
            $this->fail($e);
        }
    }

    public function failed(Throwable $exception): void
    {
        $this->importBatch->status->transitionTo(Failed::class);

        // Log the critical error for debugging
        $this->importBatch->update([
            'error_log' => [
                'critical_error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ],
        ]);

        // Send a failure notification
        Notification::make()
            ->title('Xử lý file thất bại!')
            ->body('Đã có lỗi nghiêm trọng xảy ra khi xử lý file "' . $this->importBatch->original_file_name . '".')
            ->danger()
            ->sendToDatabase($this->importBatch->uploader);
    }
}
