<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\CompanyDataController;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Str;
use App\Helpers\StringHelper;
use App\Services\FacebookConversionApi;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
        $this->app->singleton(FacebookConversionApi::class, function ($app) {
            return new FacebookConversionApi();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Inicializar el controlador de datos de la compañía
        $companyDataController = new CompanyDataController();

        // Monitoreo de jobs
        Queue::before(function (JobProcessing $event) {
            try {
                $payload = $event->job->payload();
                if (isset($payload['data']) && isset($payload['data']['command'])) {
                    $command = $payload['data']['command'];
                    $jobClass = is_object($command) ? get_class($command) : (is_string($command) ? $command : 'Unknown');
                } else {
                    $jobClass = 'Unknown (malformed payload)';
                }
                
                \Log::info('Processing job', [
                    'job' => $jobClass,
                    'queue' => $event->job->getQueue()
                ]);
            } catch (\Throwable $e) {
                \Log::error('Error logging job processing: ' . $e->getMessage());
            }
        });

        Queue::after(function (JobProcessed $event) {
            try {
                $payload = $event->job->payload();
                if (isset($payload['data']) && isset($payload['data']['command'])) {
                    $command = $payload['data']['command'];
                    $jobClass = is_object($command) ? get_class($command) : (is_string($command) ? $command : 'Unknown');
                } else {
                    $jobClass = 'Unknown (malformed payload)';
                }
                
                \Log::info('Job processed', [
                    'job' => $jobClass,
                    'queue' => $event->job->getQueue()
                ]);
            } catch (\Throwable $e) {
                \Log::error('Error logging job completion: ' . $e->getMessage());
            }
        });

        Str::macro('readDuration', function ($content, $wordsPerMinute = 200) {
            return StringHelper::readDuration($content, $wordsPerMinute);
        });
    }
}
