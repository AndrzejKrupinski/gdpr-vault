<?php

namespace App\Http\Response\Errors;

use App\Exceptions\JsonApiException;
use App\Http\Response\Errors\Strategy\DatabaseError;
use App\Http\Response\Errors\Strategy\GenericError;
use App\Http\Response\Errors\Strategy\JsonApiError;
use App\Http\Response\Errors\Strategy\JsonApiStrategy;
use App\Http\Response\Errors\Strategy\ResourceError;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Http\Response;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Throwable;

class ErrorResponseFactory
{
    /** @var array */
    protected $strategies = [
        AuthenticationException::class => GenericError::class,
        JsonApiException::class => JsonApiError::class,
        QueryException::class => DatabaseError::class,
        ResourceException::class => ResourceError::class,
    ];

    /** @var \Throwable */
    protected $exception;

    /** @var string|null */
    protected $strategy;

    /**
     * Initialize the factory and create a response using the given exception.
     *
     * @param  \Throwable  $exception
     * @return null|Response
     */
    public static function createFromException(Throwable $exception)
    {
        return (new static($exception))->create();
    }

    public function __construct(Throwable $exception, string $strategy = null)
    {
        if (!empty($strategy)) {
            $this->validateStrategy($strategy);
        }

        $this->exception = $exception;
        $this->strategy = $strategy ?: $this->resolveStrategy($exception);
    }

    /**
     * Create an error response compatible with the JSON API specification.
     *
     * @see http://jsonapi.org/format/#errors
     * @return null|Response
     */
    public function create()
    {
        if (!$strategy = $this->createStrategy()) {
            return null;
        }

        $content = [
            'errors' => [
                $strategy->toArray(),
            ],
        ];

        return new Response($content, $strategy->status());
    }

    /**
     * Set exception strategy mapping.
     *
     * @param  string  $exception
     * @param  string  $strategy
     * @return self
     */
    public function setExceptionStrategy(string $exception, string $strategy)
    {
        $this->validateStrategy($strategy);

        $this->strategies[$exception] = $strategy;

        return $this;
    }

    /**
     * @return \App\Http\Response\Errors\Strategy\JsonApiStrategy|false
     */
    protected function createStrategy()
    {
        return $this->strategy ? new $this->strategy($this->exception) : false;
    }

    /**
     * @param  \Throwable  $exception
     * @return null|string
     */
    protected function resolveStrategy(Throwable $exception)
    {
        $classes = class_parents($exception, false) + (array) get_class($exception);

        $strategy = array_first($classes, function ($class) {
            return isset($this->strategies[$class]);
        });

        return $strategy ? $this->strategies[$strategy] : null;
    }

    /**
     * @param  string  $strategy
     * @throws \InvalidArgumentException when the given strategy class is not valid
     * @return void
     */
    private function validateStrategy($strategy)
    {
        if (!is_subclass_of($strategy, JsonApiStrategy::class)) {
            throw new \InvalidArgumentException("The given strategy [$strategy] is not a valid strategy class.");
        }
    }
}
