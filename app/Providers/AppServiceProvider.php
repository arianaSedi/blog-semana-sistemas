<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Fechas en español
        Carbon::setLocale(config('app.locale', 'es'));

        // Paginacion con estilos de Bootstrap
        Paginator::useBootstrapFive();
    }
}