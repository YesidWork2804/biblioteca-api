<?php

namespace App\Providers;

use App\Domain\Repositories\AutorRepositoryInterface;
use App\Domain\Repositories\LibroRepositoryInterface;
use App\Domain\Repositories\PrestamoRepositoryInterface;
use App\Infrastructure\Persistence\EloquentAutorRepository;
use App\Infrastructure\Persistence\EloquentLibroRepository;
use App\Infrastructure\Persistence\EloquentPrestamoRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(AutorRepositoryInterface::class, EloquentAutorRepository::class);
        $this->app->bind(LibroRepositoryInterface::class, EloquentLibroRepository::class);
        $this->app->bind(PrestamoRepositoryInterface::class, EloquentPrestamoRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
