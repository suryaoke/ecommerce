<?php

namespace App\Providers;

use App\interfaces\StoreBallanceRepositoryInterface;
use App\interfaces\StoreRepositoryInterface;
use App\interfaces\UserRepositoryInterface;
use App\Repositories\StoreBallanceRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(StoreRepositoryInterface::class, StoreRepository::class);
        $this->app->bind(StoreBallanceRepositoryInterface::class, StoreBallanceRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
