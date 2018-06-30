<?php

namespace App\Http\Middleware\JsonApi;

use App\Http\Response\Format\Json as JsonFormat;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class NegotiateContentType
{
    /**
     * Handle an incoming request.
     *
     * @see http://jsonapi.org/format/#content-negotiation-servers
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     * @throws \Throwable
     */
    public function handle($request, \Closure $next)
    {
        $format = new JsonFormat($request);

        throw_unless($format->validateContentType(), UnsupportedMediaTypeHttpException::class);
        throw_unless($format->validateAccept(), NotAcceptableHttpException::class);

        return $next($request);
    }
}
