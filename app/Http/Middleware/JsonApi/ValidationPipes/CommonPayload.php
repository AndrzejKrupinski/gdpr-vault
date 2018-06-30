<?php

namespace App\Http\Middleware\JsonApi\ValidationPipes;

use App\Exceptions\InvalidJsonPayloadException;
use App\Exceptions\InvalidResourceTypeException;
use Illuminate\Http\Request;

class CommonPayload extends Pipe
{
    protected $defaultException = InvalidJsonPayloadException::class;

    protected $customExceptions = [
        'data.type' => InvalidResourceTypeException::class,
    ];

    public function __construct()
    {
        Request::macro('resourceId', function () {
            return $this->segment(2);
        });
        Request::macro('resourceName', function () {
            return $this->segment(1);
        });
    }

    public function handle(Request $request, \Closure $next)
    {
        $this->validate($request->json()->all(), $this->rules($request));

        return $next($request);
    }

    public function rules(Request $request)
    {
        return [
            'data' => 'array|required',
            'data.type' => "string|required|in:{$request->resourceName()}",
            'data.id' => 'uuid',
            'data.attributes' => 'sometimes|array|required',
            'data.relationships' => 'sometimes|array|required',
            'data.relationships.*.data' => 'array|present',
            'data.relationships.*.data.id' => 'uuid|required_with:data.relationships.*.data.type',
            'data.relationships.*.data.type' => 'string|required_with:data.relationships.*.data.id',
            'data.relationships.*.data.*.id' => 'uuid|required_with:data.relationships.*.data.*.type',
            'data.relationships.*.data.*.type' => 'string|required_with:data.relationships.*.data.*.id',
        ];
    }
}
