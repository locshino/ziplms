<?php

namespace Database\Seeders\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

trait HasSampleFiles
{
    /**
     * Returns an array of sample file URLs and names.
     *
     * @return array<int, array{url: string, name: string}>
     */
    protected function sampleFiles(): array
    {
        return [
            [
                'url' => 'https://www.w3.org/WAI/ER/tests/xhtml/testfiles/resources/pdf/dummy.pdf',
                'name' => 'dummy.pdf',
            ],
            [
                'url' => 'https://raw.githubusercontent.com/recurser/exif-orientation-examples/master/Landscape_1.jpg',
                'name' => 'landscape.jpg',
            ],
            [
                'url' => 'https://www.google.com/images/branding/googlelogo/1x/googlelogo_color_272x92dp.png',
                'name' => 'google.png',
            ],
        ];
    }

    /**
     * Attach sample files to a given model.
     *
     * @param  \Spatie\MediaLibrary\HasMedia&\Illuminate\Database\Eloquent\Model  $model
     * @param  array{min_available?: int, count?: int|array<int, int>}  $options
     */
    protected function attachSampleFiles(Model $model, string $collectionName, array $options = []): void
    {
        $minAvailable = $options['min_available'] ?? 1;
        $count = $options['count'] ?? 1;

        $sampleFiles = $this->getSampleFiles();
        $numberOfSampleFiles = count($sampleFiles);

        if ($numberOfSampleFiles < $minAvailable) {
            $this->command->warn(
                "Not enough sample files for model ID: {$model->getKey()}. ".
                "Required at least {$minAvailable}, found {$numberOfSampleFiles}."
            );

            return;
        }

        $numberOfFilesToAttach = is_array($count)
            ? rand($count[0], min($count[1], $numberOfSampleFiles))
            : min((int) $count, $numberOfSampleFiles);

        if ($numberOfFilesToAttach === 0) {
            return;
        }

        $selectedFiles = collect($sampleFiles)->shuffle()->take($numberOfFilesToAttach);

        foreach ($selectedFiles as $fileInfo) {
            try {
                $mediaAdder = ($fileInfo['path'] && file_exists($fileInfo['path']))
                    ? $model->addMedia($fileInfo['path'])
                    : $model->addMediaFromUrl($fileInfo['url']);

                $mediaAdder->preservingOriginal()
                    ->setName($fileInfo['name'])
                    ->toMediaCollection($collectionName);
            } catch (\Exception $e) {
                $errorMessage = "Failed to add media '{$fileInfo['name']}' for model ID {$model->getKey()}: ".$e->getMessage();
                Log::error($errorMessage);
                $this->command->warn($errorMessage);
            }
        }
    }

    /**
     * Get an array of sample files for seeding, using cached local files.
     *
     * @return array<int, array{url: string, path: string|null, name: string}>
     */
    protected function getSampleFiles(): array
    {
        $sampleFiles = $this->sampleFiles();
        $processedFiles = [];

        foreach ($sampleFiles as $file) {
            $cacheKey = 'seeder_get_sample_file_'.$file['name'].'_status';
            $filePath = 'sample_file/'.$file['name'];

            if (Cache::has($cacheKey) && Storage::disk('public')->exists($filePath)) {
                $processedFiles[] = [
                    'url' => config('app.url').'/storage/'.$filePath,
                    'path' => storage_path('app/public/'.$filePath),
                    'name' => $file['name'],
                ];

                continue;
            }

            Storage::disk('public')->makeDirectory('sample_file');

            try {
                $response = Http::timeout(30)->get($file['url']);
                if ($response->successful()) {
                    Storage::disk('public')->put($filePath, $response->body());
                    Cache::forever($cacheKey, true);

                    $processedFiles[] = [
                        'url' => config('app.url').'/storage/'.$filePath,
                        'path' => storage_path('app/public/'.$filePath),
                        'name' => $file['name'],
                    ];
                } else {
                    $processedFiles[] = ['url' => $file['url'], 'path' => null, 'name' => $file['name']];
                }
            } catch (\Exception $e) {
                Log::warning("Could not download sample file: {$file['name']}. Error: {$e->getMessage()}");
                $processedFiles[] = ['url' => $file['url'], 'path' => null, 'name' => $file['name']];
            }
        }

        return $processedFiles;
    }
}
