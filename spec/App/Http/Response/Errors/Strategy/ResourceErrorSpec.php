<?php

namespace spec\App\Http\Response\Errors\Strategy;

use App\Http\Response\Errors\Strategy\ResourceError;
use Dingo\Api\Exception\ResourceException;
use PhpSpec\ObjectBehavior;

class ResourceErrorSpec extends ObjectBehavior
{
    function it_is_initializable(ResourceException $exception)
    {
        $this->beConstructedWith($exception);

        $this->shouldHaveType(ResourceError::class);
    }

    function its_status_defaults_to_internal_server_error(ResourceException $exception)
    {
        $this->beConstructedWith($exception);

        $this->status()->shouldBe('500');
    }

    function it_is_representable_as_an_array()
    {
        $exception = new ResourceException('Message', ['First error', 'Second error'], null, [], 1234);
        $expected = [
            'code' => (string) $exception->getCode(),
            'detail' => 'First error',
            'status' => (string) $exception->getStatusCode(),
            'title' => 'Unprocessable resource',
        ];

        $this->beConstructedWith($exception);

        $this->toArray()->shouldBe($expected);
    }
}
