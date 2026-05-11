<?php

namespace App\Providers;

use App\Contracts\SapCustomerLookupServiceInterface;
use App\Services\SapCustomerLookupService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            SapCustomerLookupServiceInterface::class,
            SapCustomerLookupService::class
        );
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        Schema::defaultStringLength(191);
    }
}
