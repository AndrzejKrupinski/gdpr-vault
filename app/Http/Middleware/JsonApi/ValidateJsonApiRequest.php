<?php

namespace App\Http\Middleware\JsonApi;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

/**
 * @see http://jsonapi.org/format/#crud
 * @see http://jsonapi.org/format/#query-parameters
 */
class ValidateJsonApiRequest
{
    /** @var Pipeline */
    protected $pipeline;

    public function __construct(Pipeline $pipeline)
    {
        $this->pipeline = $pipeline;
    }

    /**
     * Return validation pipes applicable for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function applicablePipes(Request $request)
    {
        return array_get($this->pipes(), $request->method(), []);
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \Throwable
     */
    public function handle($request, \Closure $next)
    {
        $pipes = $this->applicablePipes($request);

        return $this->pipeline
            ->through($pipes)
            ->send($request)
            ->then(function ($request) use ($next) {
                return $next($request);
            });
    }

    /**
     * Return http methods to validation pipes mapping.
     *
     * @return array
     */
    protected function pipes()
    {
        return [
            Request::METHOD_GET => [
                ValidationPipes\QueryParameters::class,
            ],
            Request::METHOD_POST => [
                ValidationPipes\CommonPayload::class,
                ValidationPipes\PostPayload::class,
            ],
            Request::METHOD_PATCH => [
                ValidationPipes\CommonPayload::class,
                ValidationPipes\PatchPayload::class,
            ],
        ];
    }
}
