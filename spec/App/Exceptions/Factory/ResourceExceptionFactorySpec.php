<?php

namespace spec\App\Exceptions\Factory;

use App\Exceptions\Factory\ResourceExceptionFactory;
use App\Http\Requests\JsonApiRequest;
use Dingo\Api\Exception\ResourceException;
use Dingo\Api\Exception\StoreResourceFailedException;
use Dingo\Api\Exception\UpdateResourceFailedException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceExceptionFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ResourceExceptionFactory::class);
    }

    function it_creates_store_failed_exception(JsonApiRequest $request)
    {
        $request->getMethod()->willReturn('POST');
        $this->beConstructedWith($request);

        $this->create()->shouldBeAnInstanceOf(StoreResourceFailedException::class);
    }

    function it_creates_update_failed_exception(JsonApiRequest $request)
    {
        $request->getMethod()->willReturn('PATCH');
        $this->beConstructedWith($request);

        $this->create()->shouldBeAnInstanceOf(UpdateResourceFailedException::class);
    }

    function it_creates_default_exception_for_an_unhandled_request_method(JsonApiRequest $request)
    {
        $request->getMethod()->willReturn('PURGE');
        $this->beConstructedWith($request);

        $this->create()->shouldBeAnInstanceOf(ResourceException::class);
    }

    function it_handles_an_exception_message(JsonApiRequest $request)
    {
        $request->getMethod()->willReturn('GET');
        $this->beConstructedWith($request);

        $this->create('foobar')->getMessage()->shouldBe('foobar');
    }
}
