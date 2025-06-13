<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class FortifyViewsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->loadViewsFrom(__DIR__.'/../../resources/views/auth', 'auth');
    }
}
