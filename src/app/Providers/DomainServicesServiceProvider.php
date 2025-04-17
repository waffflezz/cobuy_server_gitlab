<?php

namespace App\Providers;

use App\Domain\Services\Utils\Hasher\HasherInterface;
use App\Infrastructure\Utils\LaravelHasher;
use Illuminate\Support\ServiceProvider;

class DomainServicesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(HasherInterface::class, LaravelHasher::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
