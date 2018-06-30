<?php

namespace App\Providers;

use Dingo\Api\Facade\API;
use Dingo\Api\Routing\Router;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Http\Middleware\CheckClientCredentials;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define the routes for the application.
     */
    public function map()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));

        API::router()->version('v1', function (Router $api) {

            $api->get('ping', function () {
                return API::response()->array(['pong' => now()->toAtomString()]);
            });

            $api->group([
                'middleware' => ['api', CheckClientCredentials::class],
                'namespace' => $this->namespace,
            ], function (Router $api) {
                require base_path('routes/api.php');
            });
        });
    }
}
