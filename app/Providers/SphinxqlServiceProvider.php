<?php

namespace App\Providers;

use Foolz\SphinxQL\Drivers\Mysqli\Connection;
use Foolz\SphinxQL\SphinxQL;
use Illuminate\Support\ServiceProvider;

class SphinxqlServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SphinxQL::class, function () {
            return SphinxQL::create(new Connection);
        });

        $this->app->alias(SphinxQL::class, 'sphinxql');
    }
}
