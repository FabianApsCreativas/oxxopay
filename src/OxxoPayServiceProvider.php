<?php

namespace ApsCreativas\OxxoPay;

use Illuminate\Support\ServiceProvider;

class OxxoPayServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('oxxopay', function() {
            return new OxxoPay();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__ . '/routes/routes.php';
    }
}
