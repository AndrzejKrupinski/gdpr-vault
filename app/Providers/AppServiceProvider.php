<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Vault\Filtering\Queries;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Queries::class, function ($app) {
            return new Queries($app->make('redis'));
        });
    }
}
