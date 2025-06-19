<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function downloadExport(string $path, string $extension): StreamedResponse
    {
        // Tái tạo lại đường dẫn file trong thư mục storage
        $fullPath = "exports/{$path}.{$extension}";

        // Kiểm tra file tồn tại và trả về cho người dùng
        abort_if(! Storage::disk('local')->exists($fullPath), 404);

        return Storage::disk('local')->download($fullPath);
    }
}
