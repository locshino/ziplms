<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('app')->group(function () {
    Route::get('filament-excel/exports/download/{path}/{extension}', function (string $path, string $extension) {

        // 1. Get the storage disk
        $disk = Storage::disk('filament-excel');
        $filename = $path.'.'.$extension;

        // 2. Check existence using the RELATIVE path (correct way)
        if (! $disk->exists($filename)) {
            abort(Response::HTTP_NOT_FOUND, 'File not found');
        }

        // 3. Get the ABSOLUTE path for the download response
        $absolutePath = $disk->path($filename);

        // 4. Use the dedicated download helper.
        // This streams the file efficiently without loading it all into memory.
        // It also automatically sets Content-Type and Content-Disposition headers.
        return response()
            ->download($absolutePath, $filename)
            ->deleteFileAfterSend();
    })->name('filament-excel.exports.download')->middleware('signed');

    // Media download route
    Route::get('media/{media}/download', function (\App\Models\Media $media) {
        // Check if user has permission to download this media
        if (! auth()->check()) {
            abort(401, 'Unauthorized');
        }

        // Get the model that owns this media
        $model = $media->model;

        // If it's an Assignment, check if user is enrolled in the course
        if ($model instanceof \App\Models\Assignment) {
            $user = auth()->user();
            $enrolledCourseIds = $user->enrollments()->pluck('course_id');

            if (! $enrolledCourseIds->contains($model->course_id)) {
                abort(403, 'Access denied');
            }
        }

        // Check if file exists
        $filePath = $media->getPath();
        if (! file_exists($filePath)) {
            abort(404, 'File not found: '.$media->file_name);
        }

        return response()->download($filePath, $media->file_name);
    })->name('media.download')->middleware('auth');
});
