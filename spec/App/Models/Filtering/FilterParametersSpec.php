<?php

namespace spec\App\Models\Filtering;

use PhpSpec\ObjectBehavior;

class FilterParametersSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('emails.address', 'foobar@gmail.com');
    }

    function it_returns_name_of_a_filtering_method()
    {
        $this->method()->shouldBe('filterEmailsAddress');
    }

    function it_returns_name_of_the_filter()
    {
        $this->name()->shouldBe('address');
    }

    function it_returns_name_of_the_relationship_to_which_filtration_applies()
    {
        $this->relationship()->shouldBe('emails');
    }

    function it_returns_value_of_the_filter()
    {
        $this->value()->shouldBe('foobar@gmail.com');
    }

    function it_returns_an_array_representation_of_the_filter_value()
    {
        $this->valueToArray('@')->shouldBe(['foobar', 'gmail.com']);
    }
}
