<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DownloadController extends Controller
{
    public function downloadExport(Request $request): StreamedResponse
    {
        // Validate the incoming request parameters for validity and security.
        // The 'filename' can contain a UUID and spaces, so we allow a broader pattern,
        // The validation prevents directory traversal attacks (e.g., '../').
        $validated = $request->validate([
            'filename' => ['required', 'string', 'regex:/^[a-zA-Z0-9\s._\/-]+$/', 'not_regex:/(\.\.\/|\.\.\\\\)/'], // Allow slashes, prevent directory traversal
            'extension' => ['required', 'string', 'in:xlsx,csv,pdf'], // Whitelist allowed extensions
            'download_as' => ['nullable', 'string', 'regex:/^[a-zA-Z0-9._-]+$/'],
        ]);

        $filename = $validated['filename'];
        $extension = $validated['extension'];
        // Use the desired download name if provided, otherwise fallback to the original.
        $downloadAs = $validated['download_as'] ?? "{$filename}.{$extension}";

        // Tái tạo lại đường dẫn file trong thư mục storage, theo cách filament-excel lưu trữ
        $fileFullName = "{$filename}.{$extension}";

        /** @var \Illuminate\Filesystem\FilesystemAdapter $storagePublicDisk */
        $storagePublicDisk = Storage::disk('filament-excel');

        // Check if the file exists and abort if not found.
        abort_if(! $storagePublicDisk->exists($fileFullName), 404);

        // Return the file for download, using the clean name for the user.
        return $storagePublicDisk->download($fileFullName, $downloadAs);
    }
}
