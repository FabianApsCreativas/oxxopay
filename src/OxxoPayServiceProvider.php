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
        require __DIR__ . '/functions/functions.php';
        require __DIR__ . '/routes/webhooks.php';

        $this->publishes([
            __DIR__ . '/config/oxxopay.php' => config_path('oxxopay.php')
        ], 'oxxopay');
    }
}
