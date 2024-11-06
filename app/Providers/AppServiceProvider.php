<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Services\SearchService;
use App\Interfaces\SearchServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(SearchServiceInterface::class, SearchService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
