<?php

namespace spec\App\Models\Filtering;

use App\Http\Requests\JsonApiRequest;
use App\Models\Filtering\FilterParameters;
use App\Models\Filtering\QueryFilters;
use App\Models\Filtering\Sorting;
use Illuminate\Database\Query\Builder;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vault\Filtering\Queries;

class QueryFiltersSpec extends ObjectBehavior
{
    function let(JsonApiRequest $request, Queries $queries)
    {
        $this->beAnInstanceOf(DummyQueryFilters::class);
        $this->beConstructedWith($request, $queries);
    }

    function it_throws_an_exception_when_the_given_filter_does_not_exist(Builder $builder, $request)
    {
        $filter = new FilterParameters('foo.bar', Argument::any());
        $request->filters()->willReturn([$filter]);
        $request->sorting()->willReturn(null);

        $exception = new \BadMethodCallException('Resource [foo] can\'t be filtered by [bar].');

        $this->shouldThrow($exception)->duringApply($builder);
    }

    function it_applies_sorting_when_applicable(Builder $builder, $request)
    {
        $request->filters()->willReturn([]);
        $request->sorting()->willReturn(new Sorting('foobar'));

        $this->apply($builder);

        $builder->orderBy('foobar', 'asc')->shouldHaveBeenCalled();
    }
}

class DummyQueryFilters extends QueryFilters
{
    protected $sortable = ['foobar'];
}
