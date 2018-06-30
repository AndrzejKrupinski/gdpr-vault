<?php

namespace spec\App\Http\Middleware;

use Illuminate\Http\Request;
use PhpSpec\ObjectBehavior;

class ConvertBooleanStringsSpec extends ObjectBehavior
{
    function it_converts_boolean_strings_to_literals()
    {
        $originalRequest = new Request([
            'foo' => 'true',
            'bar' => 'false',
            'baz' => 1,
            'qux' => 'foobar',
        ]);

        $transformedRequest = $this->handle($originalRequest, function ($request) {
            return $request;
        });

        $transformedRequest->all()->shouldReturn([
            'foo' => true,
            'bar' => false,
            'baz' => 1,
            'qux' => 'foobar',
        ]);
    }
}
