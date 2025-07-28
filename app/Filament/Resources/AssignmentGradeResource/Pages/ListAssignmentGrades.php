<?php

namespace App\Filament\Resources\AssignmentGradeResource\Pages;

use App\Filament\Resources\AssignmentGradeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Illuminate\Support\Facades\Storage;
use ZipArchive;


class ListAssignmentGrades extends ListRecords
{
    use ListRecords\Concerns\Translatable;
    protected static string $resource = AssignmentGradeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\LocaleSwitcher::make(),
            // Action::make('uploadForFiltered')
            //     ->label('Tải file cho tất cả bản ghi đang lọc')
            //     ->icon('heroicon-o-arrow-up-tray')
            //     ->action(function () {
            //         $records = $this->getFilteredTableQuery()->get();
            //         $files = [];
            //         foreach ($records as $grade) {
            //             $submission = $grade->submission;
            //             if (!$submission)
            //                 continue;

            //             $mediaItems = $submission->getMedia('submissions');
            //             foreach ($mediaItems as $media) {
            //                 $path = Storage::disk('public')->path($media->getPathRelativeToRoot());
            //                 if (file_exists($path)) {
            //                     $files[] = $path;
            //                 }
            //             }
            //         }

            //         if (empty($files)) {
            //             \Filament\Notifications\Notification::make()
            //                 ->title('Không tìm thấy file nào.')
            //                 ->warning()
            //                 ->send();
            //             return;
            //         }

            //         // Tạo file zip
            //         $zipFileName = 'submissions_class_' . now()->timestamp . '.zip';
            //         $zipPath = storage_path('app/public/' . $zipFileName);
            //         $zip = new ZipArchive;

            //         if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            //             foreach ($files as $file) {
            //                 $zip->addFile($file, basename($file));
            //             }
            //             $zip->close();
            //         }

            //         // Trả về đường dẫn tải
            //         return response()->download($zipPath)->deleteFileAfterSend(true);
            //     })
            //     ->requiresConfirmation()
            //     ->color('success')



        ];
    }

}
