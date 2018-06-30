<?php

namespace spec\App\Http\Response\Errors;

use App\Exceptions\JsonApiException;
use App\Exceptions\ResourceNotFoundException;
use App\Http\Response\Errors\ErrorResponseFactory;
use Dingo\Api\Exception\ResourceException;
use Illuminate\Http\Response;
use PhpSpec\ObjectBehavior;

class ErrorResponseFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new \Exception);

        $this->shouldHaveType(ErrorResponseFactory::class);
    }

    function it_throws_an_exception_when_invalid_strategy_is_provided_during_instantiation()
    {
        $this->beConstructedWith(new \Exception, 'SomeRandomStrategyClassName');

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    function it_throws_an_exception_during_mapping_an_invalid_strategy()
    {
        $this->beConstructedWith(new \Exception);

        $this->shouldThrow(\InvalidArgumentException::class)->during(
            'setExceptionStrategy',
            ['Exception', 'SomeRandomStrategyClassName']
        );
    }

    function it_creates_response_from_json_api_exception(JsonApiException $exception)
    {
        $this->beConstructedWith($exception);

        $result = $this->create();

        $result->shouldBeAnInstanceOf(Response::class);
        $result->getStatusCode()->shouldBe(500);
        $result->getContent()->shouldBe('{"errors":[{"status":"500"}]}');
    }

    function it_creates_response_from_resource_not_found_exception()
    {
        $this->beConstructedWith(new ResourceNotFoundException('foobar'));

        $result = $this->create();

        $result->shouldBeAnInstanceOf(Response::class);
        $result->getStatusCode()->shouldBe(404);
        $result->getContent()->shouldBe('{"errors":[{"detail":"foobar","status":"404"}]}');
    }

    function it_creates_response_from_resource_exception()
    {
        $this->beConstructedWith(new ResourceException);

        $result = $this->create();

        $result->shouldBeAnInstanceOf(Response::class);
        $result->getStatusCode()->shouldBe(422);
        $result->getContent()->shouldBe('{"errors":[{"status":"422","title":"Unprocessable resource"}]}');
    }
}
