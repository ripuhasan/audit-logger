<?php

namespace MahedulHasan\AuditLogger;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\File;
use MahedulHasan\AuditLogger\Observers\AuditObserver;

class AuditLoggerServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // publish migration
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // publish view
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'auditlogger');

        // publish route
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // auto-observe all models (optional)
        $modelPath = app_path('Models');
        $modelNamespace = 'App\\Models\\';

        foreach (File::allFiles($modelPath) as $file) {
            $model = $modelNamespace . pathinfo($file->getFilename(), PATHINFO_FILENAME);
            if (class_exists($model)) {
                $model::observe(AuditObserver::class);
            }
        }
    }

    public function register(): void
    {
        //
    }
}
