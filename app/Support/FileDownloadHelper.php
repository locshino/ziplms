<?php

namespace App\Support;

use Illuminate\Support\Facades\URL;

class FileDownloadHelper
{
    /**
     * Generates a WAF-friendly temporary signed URL for file downloads.
     *
     * @param  string  $filePath  The full path to the file on disk (e.g., 'imports/failures/error_report_123.xlsx').
     * @param  bool  $isErrorReport  If true, skips UUID removal and sanitization for friendly name.
     * @param  int  $expirationHours  The number of hours the URL should be valid.
     * @return string The generated signed URL.
     */
    public static function generateWafFriendlySignedUrl(
        string $filePath,
        bool $isErrorReport = false,
        int $expirationHours = 2
    ): string {
        $fileInfo = pathinfo($filePath);
        $filenameWithoutExtension = $fileInfo['filename'];
        $extension = $fileInfo['extension'];

        if ($isErrorReport) {
            // For error reports, the filename is usually already clean.
            $friendlyName = $filenameWithoutExtension;
        } else {
            // For general exports, remove UUID prefix and sanitize for a cleaner download name.
            $friendlyName = preg_replace('/^[a-f0-9]{8}-([a-f0-9]{4}-){3}[a-f0-9]{12}-/i', '', $filenameWithoutExtension);
            $friendlyName = preg_replace('/[\s-]+/', '-', $friendlyName);
        }

        return URL::temporarySignedRoute(
            'exports.download',
            now()->addHours($expirationHours),
            [
                'filename' => $filenameWithoutExtension,
                'extension' => $extension,
                'download_as' => $friendlyName.'.'.$extension,
            ]
        );
    }
}
