<?php

namespace App\Http\Middleware\JsonApi\ValidationPipes;

use App\Exceptions\UnprocessableParameterException;
use App\Services\Validation\Rules\FilterParameter;
use App\Services\Validation\Rules\SortParameter;
use Illuminate\Http\Request;

class QueryParameters extends Pipe
{
    protected $defaultException = UnprocessableParameterException::class;

    public function handle(Request $request, \Closure $next)
    {
        $this->validate($request->all(), $this->rules($request));

        return $next($request);
    }

    public function rules(Request $request)
    {
        return [
            'filter' => new FilterParameter,
            'filter.*' => 'required_with:filter',
            'sort' => new SortParameter,
        ];
    }
}
