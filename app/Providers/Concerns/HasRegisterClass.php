<?php

namespace App\Providers\Concerns;

use Illuminate\Support\Facades\File;

trait HasRegisterClass
{
    /**
     * Scans a directory for classes and executes a callback for each valid class found.
     * This generic function is highly reusable for auto-registering services, observers, etc.
     *
     * @param string $path The directory path to scan.
     * @param string $namespace The base namespace for the classes in the directory.
     * @param callable $registrationLogic The callback to execute for each class. It receives the fully qualified class name.
     * @param array $skipClasses An array of simple class names (without namespace) to skip.
     */
    protected function discoverAndRegister(
        string $path,
        string $namespace,
        callable $registrationLogic,
        array $skipClasses = []
    ): void {
        if (!File::isDirectory($path)) {
            return;
        }

        try {
            $files = File::files($path);

            foreach ($files as $file) {
                $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);

                // Skip any classes in the skip list
                if (in_array($className, $skipClasses, true)) {
                    continue;
                }

                $class = "{$namespace}\\{$className}";

                // Ensure the class exists before attempting to register it
                if (class_exists($class)) {
                    // Execute the provided registration logic for the discovered class
                    $registrationLogic($class);
                }
            }
        } catch (\Exception $e) {
            // In a real application, you might want to log this error
            // logger()->error('Failed to discover and register classes: ' . $path, ['error' => $e->getMessage()]);
        }
    }
}
