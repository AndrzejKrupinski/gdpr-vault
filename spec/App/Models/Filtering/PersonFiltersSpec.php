<?php

namespace spec\App\Models\Filtering;

use App\Http\Requests\JsonApiRequest;
use App\Models\Filtering\FilterParameters;
use Illuminate\Database\Query\Builder;
use PhpSpec\ObjectBehavior;
use Vault\Filtering\Queries;

class PersonFiltersSpec extends ObjectBehavior
{
    function let(JsonApiRequest $request, Queries $queries)
    {
        $this->beConstructedWith($request, $queries);
    }

    function it_filters_by_id(Builder $builder, FilterParameters $filter)
    {
        $filter->valueToArray()->willReturn([123, 5123, 9872]);

        $this->filterId($builder, $filter);

        $builder->whereIn('id', [123, 5123, 9872])->shouldHaveBeenCalled();
    }

    function it_filters_by_site(Builder $builder, FilterParameters $filter)
    {
        $filter->valueToArray()->willReturn([123, 5123, 9872]);

        $this->filterSite($builder, $filter);

        $builder->whereIn('site', [123, 5123, 9872])->shouldHaveBeenCalled();
    }
}
