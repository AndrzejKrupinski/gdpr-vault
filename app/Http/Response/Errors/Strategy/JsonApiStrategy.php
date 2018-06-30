<?php

namespace App\Http\Response\Errors\Strategy;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Response;

abstract class JsonApiStrategy implements Arrayable
{
    /** @var int Default HTTP status code */
    const DEFAULT_STATUS = Response::HTTP_INTERNAL_SERVER_ERROR;

    /** @var \Exception */
    protected $exception;

    /** @var int[] provides exceptions, corresponding to specific HTTP status codes */
    protected $exceptionStatuses = [];

    public function __construct(\Exception $exception)
    {
        $this->exception = $exception;
    }

    /**
     * An application-specific error code.
     *
     * @return null|string
     */
    public function code(): ?string
    {
        return null;
    }

    /**
     * The HTTP status code applicable to this problem.
     *
     * @return string
     */
    public function status(): string
    {
        $defaultStatus = $this->statusCode() ?: $this->exception->getCode() ?: static::DEFAULT_STATUS;

        return array_get($this->exceptionStatuses, get_class($this->exception), $defaultStatus);
    }

    /**
     * Return a short, human-readable summary of the problem.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->exception->getMessage();
    }

    /**
     * Return a human-readable explanation specific to this occurrence of the problem.
     *
     * @return null|string
     */
    public function detail(): ?string
    {
        return null;
    }

    public function toArray()
    {
        return array_filter([
            'code' => $this->code(),
            'detail' => $this->detail(),
            'status' => $this->status(),
            'title' => $this->title(),
        ]);
    }

    /**
     * Return a status code from the exception when available.
     *
     * @return null|string
     */
    protected function statusCode(): ?string
    {
        if (!method_exists($this->exception, 'getStatusCode')) {
            return null;
        }

        return call_user_func([$this->exception, 'getStatusCode']);
    }
}
