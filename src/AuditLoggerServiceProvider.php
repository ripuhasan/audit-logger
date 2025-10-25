<?php

namespace MahedulHasan\AuditLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use MahedulHasan\AuditLogger\Observers\AuditObserver;

class AuditLoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Load migrations, views, and routes
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'auditlogger');
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');

        // Register observers dynamically
        $this->registerModelObservers();
    }

    public function register(): void
    {
        //
    }

    /**
     * Automatically attach observer to all Eloquent models
     * found under app/Models and Modules/*
     */
    protected function registerModelObservers(): void
    {
        // Possible model directories
        $directories = [
            app_path('Models'),
            base_path('Modules'),
        ];

        foreach ($directories as $dir) {
            if (!File::exists($dir)) continue;

            // If Modules directory, dig deeper into each module
            if (basename($dir) === 'Modules') {
                foreach (File::directories($dir) as $moduleDir) {
                    $modelDir = $moduleDir . '/Models';
                    if (File::exists($modelDir)) {
                        $this->observeModelsIn($modelDir);
                    }
                }
            } else {
                $this->observeModelsIn($dir);
            }
        }
    }

    /**
     * Attach observer to all models inside a specific directory
     */
    protected function observeModelsIn(string $directory): void
    {
        foreach (File::allFiles($directory) as $file) {
            $class = $this->resolveFullClassName($file);
            if (
                $class &&
                class_exists($class) &&
                is_subclass_of($class, \Illuminate\Database\Eloquent\Model::class)
            ) {
                try {
                    $class::observe(AuditObserver::class);
                } catch (\Throwable $e) {
                    // Skip invalid model files
                }
            }
        }
    }

    /**
     * Convert file path to fully qualified class name dynamically
     */
    protected function resolveFullClassName($file): ?string
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $psr4 = $composer['autoload']['psr-4'] ?? [];

        foreach ($psr4 as $namespace => $path) {
            $base = realpath(base_path(trim($path, '/')));
            $filePath = realpath($file->getPath());

            if (Str::startsWith($filePath, $base)) {
                $relative = Str::replaceFirst($base, '', $filePath);
                $relative = trim(str_replace('/', '\\', $relative), '\\');
                return trim($namespace . '\\' . pathinfo($file->getFilename(), PATHINFO_FILENAME), '\\');
            }
        }

        return null;
    }
}
