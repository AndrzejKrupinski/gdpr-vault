<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\ServiceProvider;

class QueryBuilderServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Builder::macro('dd', function () {
            dd(vsprintf(str_replace('?', '%s', $this->toSql()), $this->getBindings()));
        });

        Builder::macro('replaceGlobalScope', function (Scope $scope) {
            $identifier = get_class($scope);

            return $this->withoutGlobalScope($identifier)
                ->withGlobalScope($identifier, $scope);
        });
    }
}
