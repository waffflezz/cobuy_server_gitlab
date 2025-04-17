<?php

namespace App\Providers;

use App\Domain\UseCase\Auth\RegisterUseCase;
use App\Domain\UseCase\Auth\RegisterUseCaseInterface;
use App\Domain\UseCase\Auth\UserRepositoryInterface;
use App\Infrastructure\Repository\User\UserRepository;
use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(RegisterUseCaseInterface::class, RegisterUseCase::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
