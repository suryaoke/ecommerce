<?php

namespace App\Providers;

use App\interfaces\StoreBallanceHistoryRepositoryInterface;
use App\interfaces\StoreBallanceRepositoryInterface;
use App\interfaces\StoreRepositoryInterface;
use App\interfaces\UserRepositoryInterface;
use App\interfaces\WithdrawalRepositoryInterface;
use App\Repositories\StoreBallanceRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use App\Repositories\WithdrawalRepository;
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
        $this->app->bind(StoreBallanceHistoryRepositoryInterface::class, StoreBallanceHistoryRepositoryInterface::class);
        $this->app->bind(WithdrawalRepositoryInterface::class, WithdrawalRepository::class);
        }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
