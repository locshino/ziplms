<?php

use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/exports/download/{path}.{extension}', [DownloadController::class, 'downloadExport'])
    ->name('exports.download')
    ->middleware(['web', 'auth']); // Đảm bảo người dùng đã đăng nhập
