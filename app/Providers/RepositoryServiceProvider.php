<?php

namespace App\Providers;

use App\interfaces\BuyerRepositoryInterface;
use App\interfaces\StoreBalanceHistoryRepositoryInterface;
use App\interfaces\StoreBalanceRepositoryInterface;
use App\interfaces\StoreRepositoryInterface;
use App\interfaces\UserRepositoryInterface;
use App\interfaces\WithdrawalRepositoryInterface;
use App\Repositories\BuyerRepository;
use App\Repositories\StoreBalanceHistoryRepository;
use App\Repositories\StoreBalanceRepository;
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
        $this->app->bind(StoreBalanceRepositoryInterface::class, StoreBalanceRepository::class);
        $this->app->bind(StoreBalanceHistoryRepositoryInterface::class, StoreBalanceHistoryRepositoryInterface::class);
        $this->app->bind(WithdrawalRepositoryInterface::class, WithdrawalRepository::class);
        $this->app->bind(StoreBalanceHistoryRepositoryInterface::class, StoreBalanceHistoryRepository::class);
        $this->app->bind(BuyerRepositoryInterface::class, BuyerRepository::class);
        }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
