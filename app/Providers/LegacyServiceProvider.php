<?php

namespace App\Providers;

use App\Services\LegacyHasher\LegacyHasher;
use Illuminate\Support\ServiceProvider;

class LegacyServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('hash.legacy', function () {
            return new LegacyHasher;
        });
    }
}
