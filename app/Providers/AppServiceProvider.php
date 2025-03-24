<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\CompanyDataController;
use Illuminate\Support\Facades\Queue;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Queue\Events\JobProcessing;
use Illuminate\Support\Str;
use App\Helpers\StringHelper;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
            \Log::info('Processing job', [
                'job' => get_class($event->job->payload()['data']['command']),
                'queue' => $event->job->getQueue()
            ]);
        });

        Queue::after(function (JobProcessed $event) {
            \Log::info('Job processed', [
                'job' => get_class($event->job->payload()['data']['command']),
                'queue' => $event->job->getQueue()
            ]);
        });

        Str::macro('readDuration', function ($content, $wordsPerMinute = 200) {
            return StringHelper::readDuration($content, $wordsPerMinute);
        });
    }
}
