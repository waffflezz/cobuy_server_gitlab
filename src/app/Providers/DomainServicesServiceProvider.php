<?php

namespace App\Providers;

use App\Domain\Services\Auth\AuthorizeServiceInterface;
use App\Domain\Services\Broadcast\BroadcastServiceInterface;
use App\Domain\Services\File\FileStorageInterface;
use App\Domain\Services\Utils\Hasher\HasherInterface;
use App\Infrastructure\File\LaravelFileStorage;
use App\Infrastructure\Services\AuthorizeService;
use App\Infrastructure\Services\BroadcastService;
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
        $this->app->bind(FileStorageInterface::class, LaravelFileStorage::class);
        $this->app->bind(BroadcastServiceInterface::class, BroadcastService::class);
        $this->app->bind(AuthorizeServiceInterface::class, AuthorizeService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
