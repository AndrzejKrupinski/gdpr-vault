<?php

namespace spec\App\Http\Middleware\JsonApi;

use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use PhpSpec\ObjectBehavior;

class ValidateJsonApiRequestSpec extends ObjectBehavior
{
    function let(Pipeline $pipeline)
    {
        $this->beConstructedWith($pipeline);
    }

    function it_returns_pipes_applicable_for_get_request(Request $request)
    {
        $request->method()->willReturn(Request::METHOD_GET);

        $this->applicablePipes($request)->shouldBe([
            'App\Http\Middleware\JsonApi\ValidationPipes\QueryParameters',
        ]);
    }

    function it_returns_pipes_applicable_for_post_request(Request $request)
    {
        $request->method()->willReturn(Request::METHOD_POST);

        $this->applicablePipes($request)->shouldBe([
            'App\Http\Middleware\JsonApi\ValidationPipes\CommonPayload',
            'App\Http\Middleware\JsonApi\ValidationPipes\PostPayload',
        ]);
    }

    function it_returns_pipes_applicable_for_patch_request(Request $request)
    {
        $request->method()->willReturn(Request::METHOD_PATCH);

        $this->applicablePipes($request)->shouldBe([
            'App\Http\Middleware\JsonApi\ValidationPipes\CommonPayload',
            'App\Http\Middleware\JsonApi\ValidationPipes\PatchPayload',
        ]);
    }
}
