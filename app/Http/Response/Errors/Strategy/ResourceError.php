<?php

namespace App\Http\Response\Errors\Strategy;

use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Response;

class ResourceError extends JsonApiStrategy
{
    /** @var \Dingo\Api\Exception\ResourceException */
    protected $exception;

    /** @var int[] provides exceptions, corresponding to specific HTTP status codes */
    protected $exceptionStatuses = [
        StoreResourceFailedException::class => Response::HTTP_FORBIDDEN,
        UpdateResourceFailedException::class => Response::HTTP_FORBIDDEN,
    ];

    /** @var array provides exceptions with corresponding error titles */
    protected $titles = [
        ResourceException::class => 'Unprocessable resource',
        StoreResourceFailedException::class => 'Resource could not be stored',
        UpdateResourceFailedException::class => 'Resource could not be updated',
        ValidationHttpException::class => 'Resource validation failed',
    ];

    public function __construct(ResourceException $exception)
    {
        parent::__construct($exception);
    }

    public function code(): ?string
    {
        return $this->exception->getCode();
    }

    public function detail(): ?string
    {
        if ($this->exception->hasErrors()) {
            return $this->exception->getErrors()->first();
        }

        return $this->exception->getMessage();
    }

    public function title(): string
    {
        return array_get($this->titles, get_class($this->exception), __CLASS__);
    }
}
