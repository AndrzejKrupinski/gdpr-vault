<?php

namespace App\Http\Middleware\JsonApi\ValidationPipes;

use Dingo\Api\Exception\ValidationHttpException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

abstract class Pipe
{
    /** @var string The default exception to be thrown when validation fails. */
    protected $defaultException = ValidationHttpException::class;

    /** @var array Validation errors and exceptions specific to them. */
    protected $customExceptions = [];

    /**
     * Handle validation of the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    abstract public function handle(Request $request, \Closure $next);

    /**
     * Return validation rules.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    abstract public function rules(Request $request);

    /**
     * @param  array  $data
     * @param  array  $rules
     * @throws \Throwable if the data fails the validation rules
     */
    protected function validate($data, $rules)
    {
        $validator = Validator::make($data, $rules);

        throw_if($validator->fails(), $this->createException($validator->errors()));
    }

    /**
     * Create a new exception based on given error messages.
     *
     * @param  \Illuminate\Support\MessageBag  $messages
     * @return \Exception
     */
    private function createException($messages)
    {
        $shouldUseCustomException = function ($exception, $error) use ($messages) {
            return $messages->has($error);
        };

        $exception = array_first(
            $this->customExceptions,
            $shouldUseCustomException,
            $this->defaultException
        );

        if ($exception === ValidationHttpException::class) {
            return new $exception($messages);
        }

        return new $exception(implode(' ', $messages->unique()));
    }
}
