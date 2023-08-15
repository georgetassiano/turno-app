<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
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
        $this->app->bind(\App\Repositories\AccountRepository::class, \App\Repositories\AccountRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\CheckRepository::class, \App\Repositories\CheckRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\ExpenseRepository::class, \App\Repositories\ExpenseRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\TransactionRepository::class, \App\Repositories\TransactionRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\UserRepository::class, \App\Repositories\UserRepositoryEloquent::class);
        $this->app->bind(\App\Repositories\AdminRepository::class, \App\Repositories\AdminRepositoryEloquent::class);
        //:end-bindings:
    }
}
