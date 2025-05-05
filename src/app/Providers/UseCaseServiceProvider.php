<?php

namespace App\Providers;

use App\Domain\UseCase\Auth\RegisterUseCase;
use App\Domain\UseCase\Auth\RegisterUseCaseInterface;
use App\Domain\UseCase\Group\GroupRepositoryInterface;
use App\Domain\UseCase\Group\GroupUseCase;
use App\Domain\UseCase\Group\GroupUseCaseInterface;
use App\Domain\UseCase\Product\ProductRepositoryInterface;
use App\Domain\UseCase\Product\ProductUseCase;
use App\Domain\UseCase\Product\ProductUseCaseInterface;
use App\Domain\UseCase\ShoppingList\ShoppingListRepositoryInterface;
use App\Domain\UseCase\ShoppingList\ShoppingListUseCase;
use App\Domain\UseCase\ShoppingList\ShoppingListUseCaseInterface;
use App\Domain\UseCase\User\UserRepositoryInterface;
use App\Infrastructure\Repository\GroupRepository;
use App\Infrastructure\Repository\ProductRepository;
use App\Infrastructure\Repository\ShoppingListRepository;
use App\Infrastructure\Repository\UserRepository;
use Illuminate\Support\ServiceProvider;

class UseCaseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register | User use cases
        $this->app->bind(RegisterUseCaseInterface::class, RegisterUseCase::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);

        // Group use case
        $this->app->bind(GroupUseCaseInterface::class, GroupUseCase::class);
        $this->app->bind(GroupRepositoryInterface::class, GroupRepository::class);

        // ShoppingList use case
        $this->app->bind(ShoppingListUseCaseInterface::class, ShoppingListUseCase::class);
        $this->app->bind(ShoppingListRepositoryInterface::class, ShoppingListRepository::class);

        // Product
        $this->app->bind(ProductUseCaseInterface::class, ProductUseCase::class);
        $this->app->bind(ProductRepositoryInterface::class, ProductRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
