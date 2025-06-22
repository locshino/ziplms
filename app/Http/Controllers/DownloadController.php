<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function downloadExport(string $path, string $extension): StreamedResponse
    {
        // Tái tạo lại đường dẫn file trong thư mục storage, theo cách filament-excel lưu trữ
        $fullPath = "filament-excel/{$path}.{$extension}";

        /** @var \Illuminate\Filesystem\FilesystemAdapter $storagePublicDisk */
        $storagePublicDisk = Storage::disk('public');

        // Kiểm tra file tồn tại và trả về cho người dùng
        abort_if(! $storagePublicDisk->exists($fullPath), 404);

        return $storagePublicDisk->download($fullPath);
    }
}
