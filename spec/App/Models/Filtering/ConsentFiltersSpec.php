<?php

namespace spec\App\Models\Filtering;

use App\Http\Requests\JsonApiRequest;
use App\Models\Filtering\FilterParameters;
use Illuminate\Database\Eloquent\Builder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vault\Filtering\Queries;

class ConsentFiltersSpec extends ObjectBehavior
{
    function let(JsonApiRequest $request, Queries $queries)
    {
        $this->beConstructedWith($request, $queries);
    }

    function it_filters_by_a_confirmation_state(Builder $builder, FilterParameters $filter)
    {
        $filter->value()->willReturn(true);

        $this->filterConfirmed($builder, $filter);

        $builder->where('confirmed', true)->shouldHaveBeenCalled();
    }

    function it_filters_by_a_purpose(Builder $builder, FilterParameters $filter)
    {
        $this->filterPurpose($builder, $filter);

        $builder->whereHas('purposes', Argument::type('closure'))->shouldHaveBeenCalled();
    }
}
