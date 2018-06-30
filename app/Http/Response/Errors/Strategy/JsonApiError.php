<?php

namespace App\Http\Response\Errors\Strategy;

use App\Exceptions\JsonApiException;

class JsonApiError extends JsonApiStrategy
{
    /** @var JsonApiException */
    protected $exception;

    public function __construct(JsonApiException $exception)
    {
        parent::__construct($exception);
    }

    public function code(): ?string
    {
        return $this->exception->getCode();
    }

    public function status(): string
    {
        return $this->exception->getStatusCode() ?: static::DEFAULT_STATUS;
    }

    public function detail(): ?string
    {
        return $this->exception->getDetail();
    }
}
