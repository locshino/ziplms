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

});
