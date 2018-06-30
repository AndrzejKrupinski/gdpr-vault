<?php

namespace App\Exceptions\Factory;

use App\Exceptions\ResourceNotFoundException;
use Dingo\Api\Exception\DeleteResourceFailedException;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Illuminate\Http\Request;

class ResourceExceptionFactory
{
    /** @var string */
    protected $default = ResourceException::class;

    /** @var array */
    protected $exceptions = [
        Request::METHOD_DELETE => DeleteResourceFailedException::class,
        Request::METHOD_PATCH => UpdateResourceFailedException::class,
        Request::METHOD_POST => StoreResourceFailedException::class,
    ];

    /** @var Request */
    protected $request;

    public function __construct(Request $request = null)
    {
        $this->request = $request ?: request();
    }

    /**
     * Create a new exception from a http method.
     *
     * @param  string|null  $message
     * @return ResourceException
     */
    public function create($message = null)
    {
        $exception = array_get($this->exceptions, $this->request->getMethod(), $this->default);

        return new $exception($message);
    }

    /**
     * @param  string|null  $message
     * @return \App\Exceptions\ResourceNotFoundException
     */
    public function createNotFound($message = null)
    {
        return new ResourceNotFoundException($message);
    }
}
