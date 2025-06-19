<?php

use App\Http\Controllers\DownloadController;

Route::get('/download/export/{path}.{extension}', [DownloadController::class, 'downloadExport'])
    ->name('exports.download')
    ->middleware(['web', 'auth']); // Đảm bảo người dùng đã đăng nhập
