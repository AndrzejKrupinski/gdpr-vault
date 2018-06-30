<?php

namespace spec\App\Http\Response\Errors\Strategy;

use App\Http\Response\Errors\Strategy\GenericError;
use PhpSpec\ObjectBehavior;

class GenericErrorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new \Exception);

        $this->shouldHaveType(GenericError::class);
    }

    function it_does_not_have_detail()
    {
        $this->beConstructedWith(new \Exception);

        $this->detail()->shouldBeNull();
    }

    function it_does_not_have_code()
    {
        $this->beConstructedWith(new \Exception);

        $this->code()->shouldBeNull();
    }

    function its_status_defaults_to_internal_server_error()
    {
        $this->beConstructedWith(new \Exception);

        $this->status()->shouldBe('500');
    }

    function it_is_representable_as_an_array()
    {
        $this->beConstructedWith(new \Exception('Message', 1234));

        $this->toArray()->shouldBe(['status' => '1234', 'title' => 'Message']);
    }
}
