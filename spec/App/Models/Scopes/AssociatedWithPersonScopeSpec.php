<?php

namespace spec\App\Models\Scopes;

use App\Models\Consent;
use App\Models\Model;
use Illuminate\Database\Eloquent\Builder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AssociatedWithPersonScopeSpec extends ObjectBehavior
{
    function let($builder)
    {
        $builder->beADoubleOf(Builder::class);
    }

    function it_throws_an_exception_if_the_person_relationship_is_not_defined($builder, Model $model)
    {
        $this->shouldThrow(\LogicException::class)->during('apply', [$builder, $model]);
    }

    function its_querying_relationship_existence_with_any_person($builder, Consent $model)
    {
        $this->apply($builder, $model);

        $builder->has('person')->shouldHaveBeenCalled();
    }

    function its_querying_relationship_existence_with_the_given_person($builder, Consent $model)
    {
        $this->beConstructedWith(Argument::type('string'));

        $this->apply($builder, $model);

        $builder->whereHas('person', Argument::type('closure'))->shouldHaveBeenCalled();
    }
}
