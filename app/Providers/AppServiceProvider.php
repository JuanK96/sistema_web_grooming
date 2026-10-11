<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\ClienteRepositoryInterface::class,
            \App\Repositories\EloquentClienteRepository::class
        );
        $this->app->bind(
            \App\Repositories\MascotaRepositoryInterface::class,
            \App\Repositories\EloquentMascotaRepository::class
        );
        $this->app->bind(
            \App\Repositories\CitaRepositoryInterface::class,
            \App\Repositories\EloquentCitaRepository::class
        );
        $this->app->bind(
            \App\Repositories\HistorialRepositoryInterface::class,
            \App\Repositories\EloquentHistorialRepository::class
        );
        $this->app->bind(
            \App\Repositories\UsuarioRepositoryInterface::class,
            \App\Repositories\EloquentUsuarioRepository::class
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
