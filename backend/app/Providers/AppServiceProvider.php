<?php

namespace App\Providers;

use App\Models\Document;
use App\Services\FileUploadService;
use App\Services\RequestService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(FileUploadService::class, function () {
            $document = new Document();
            return new FileUploadService($document);
        });

        $this->app->bind(RequestService::class, function () {
            return new RequestService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
