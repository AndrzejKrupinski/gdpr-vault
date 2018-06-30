<?php

namespace App\Providers;

use App\Http\Response\Errors\ErrorResponseFactory;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\ServiceProvider;
use League\Fractal\Manager as FractalManager;
use League\Fractal\Serializer\JsonApiSerializer;

class ApiServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->bootExceptionHandler();
        $this->bootFractal();
        $this->bootUrlGenerator();
    }

    protected function bootExceptionHandler()
    {
        $this->app->make('Dingo\Api\Exception\Handler')->register(function (\Throwable $exception) {
            return ErrorResponseFactory::createFromException($exception);
        });
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    protected function bootFractal()
    {
        $this->app->make('Dingo\Api\Transformer\Factory')->setAdapter(function () {
            $transformer = config('api.transformer');
            $fractal = (new FractalManager)->setSerializer(new JsonApiSerializer);

            return new $transformer($fractal);
        });
    }

    protected function bootUrlGenerator()
    {
        $urlGenerator = $this->app->make(UrlGenerator::class);

        $urlGenerator::macro('resource', function ($type, $id, $route = null) {
            return $this->route($route ?: "{$type}.show", [$type => $id], false);
        });
    }
}
