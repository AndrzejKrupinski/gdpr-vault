<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        $this->bootPassport();

        Auth::provider('legacy', function ($app) {
            return $app->make(LegacyUserProvider::class);
        });
    }

    /**
     * Configure Passport
     *
     * @return void
     */
    protected function bootPassport()
    {
        Passport::routes();
        Passport::tokensExpireIn(config('auth.passport.token_expiration.access'));
        Passport::refreshTokensExpireIn(config('auth.passport.token_expiration.refresh'));
        Passport::tokensCan([
            'read_person' => 'Read person data',
            'write_person' => 'Write person data',
        ]);
    }
}
