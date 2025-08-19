<?php

namespace App\Providers\Concerns;

use Illuminate\Support\Facades\File;

trait HasRegisterClass
{
    /**
     * Generic method to register classes from a directory with their interfaces.
     *
     * @param string $path The directory path to scan
     * @param string $namespace The namespace of the implementation classes
     * @param string $interfaceNamespace The namespace of the interface classes
     * @param array $skipClasses Array of class names to skip during registration
     */
    protected function registerClassesFromDirectory(
        string $path,
        string $namespace,
        string $interfaceNamespace,
        array $skipClasses = []
    ): void {
        // Check if directory exists before proceeding
        if (!File::isDirectory($path)) {
            return;
        }

        try {
            // Get all PHP files in the directory
            $files = File::files($path);

            foreach ($files as $file) {
                $this->registerClassFromFile(
                    file: $file,
                    namespace: $namespace,
                    interfaceNamespace: $interfaceNamespace,
                    skipClasses: $skipClasses
                );
            }
        } catch (\Exception $e) {
            // Log error but don't break the application
            // In production, you might want to log this error
            // logger()->error('Failed to register classes from directory: ' . $path, ['error' => $e->getMessage()]);
        }
    }

    /**
     * Register a single class from file with its interface.
     *
     * @param \SplFileInfo $file The file object
     * @param string $namespace The namespace of the implementation class
     * @param string $interfaceNamespace The namespace of the interface class
     * @param array $skipClasses Array of class names to skip
     */
    protected function registerClassFromFile(
        \SplFileInfo $file,
        string $namespace,
        string $interfaceNamespace,
        array $skipClasses
    ): void {
        // Extract class name from filename (without .php extension)
        $className = pathinfo($file->getFilename(), PATHINFO_FILENAME);

        // Skip base classes and other specified classes
        if (in_array($className, $skipClasses, true)) {
            return;
        }

        // Construct fully qualified class names
        $implementationClass = "{$namespace}\\{$className}";
        $interfaceClass = "{$interfaceNamespace}\\{$className}Interface";

        // Only bind if both class and interface exist
        if (class_exists($implementationClass) && interface_exists($interfaceClass)) {
            $this->app->bind($interfaceClass, $implementationClass);
        }
    }
}