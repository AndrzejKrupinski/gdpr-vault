<?php

namespace App\Http\Response\Errors\Strategy;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Response;

class GenericError extends JsonApiStrategy
{
    /** @var int[] provides exceptions, corresponding to specific HTTP status codes */
    protected $exceptionStatuses = [
        AuthenticationException::class => Response::HTTP_UNAUTHORIZED,
    ];
}
