<?php

namespace App\Providers;

use App\View\Composers\MenuComposer;
use Illuminate\Support\ServiceProvider;

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
        // Register view composers
        view()->composer('adminlte::page', MenuComposer::class);
    }
}

