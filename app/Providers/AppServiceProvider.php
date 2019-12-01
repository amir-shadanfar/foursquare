<?php

namespace App\Providers;

use App\Classes\ApiClass;
use App\Interfaces\ApiInterface;
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
        // bind interface to class
        $this->app->bind(ApiInterface::class, ApiClass::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
