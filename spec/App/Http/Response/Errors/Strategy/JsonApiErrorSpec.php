<?php

namespace spec\App\Http\Response\Errors\Strategy;

use App\Http\Response\Errors\Strategy\JsonApiError;
use App\Exceptions\JsonApiException;
use PhpSpec\ObjectBehavior;

class JsonApiErrorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new JsonApiException(404, 'Title'));

        $this->shouldHaveType(JsonApiError::class);
    }

    function its_status_defaults_to_internal_server_error()
    {
        $this->beConstructedWith(new JsonApiException(0, 'Title'));

        $this->status()->shouldBe('500');
    }

    function it_is_representable_as_an_array()
    {
        $this->beConstructedWith(new JsonApiException(404, 'Title', 'Description', 1234));

        $this->toArray()->shouldBe([
            'code' => '1234',
            'detail' => 'Description',
            'status' => '404',
            'title' => 'Title',
        ]);
    }
}
