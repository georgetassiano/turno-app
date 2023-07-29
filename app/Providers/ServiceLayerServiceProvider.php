<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ServiceLayerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->bind(\App\Services\UserAuthServiceInterface::class, \App\Services\UserAuthService::class);
        $this->app->bind(\App\Services\UserServiceInterface::class, \App\Services\UserService::class);
        $this->app->bind(\App\Services\AccountServiceInterface::class, \App\Services\AccountService::class);
        $this->app->bind(\App\Services\TransactionServiceInterface::class, \App\Services\TransactionService::class);
        $this->app->bind(\App\Services\DepositServiceInterface::class, \App\Services\DepositService::class);
        $this->app->bind(\App\Services\ExpenseServiceInterface::class, \App\Services\ExpenseService::class);
        //:end-bindings:
    }
}
