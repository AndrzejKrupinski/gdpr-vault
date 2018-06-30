<?php

namespace App\Models\Filtering;

use App\Exceptions\Factory\ResourceExceptionFactory;
use App\Http\Requests\JsonApiRequest;
use App\Models\Model;
use Vault\Filtering\Queries;
use WebGarden\Model\ValueObject\Identity\Uuid;

abstract class QueryFilters
{
    protected const UUID_VALIDATION_RULES = 'bail|string|uuid';

    /** @var \App\Http\Requests\JsonApiRequest */
    protected $request;

    /** @var array List of sortable columns */
    protected $sortable = [];

    /** @var \Vault\Filtering\Queries */
    protected $queries;

    /** @var array Filter name to validation rules mappings */
    protected $validationRules = [];

    /** @var array */
    private $globalValidationRules = [
        'id' => 'list',
        'tag' => 'list',
        'query' => self::UUID_VALIDATION_RULES,
    ];

    /**
     * @param  \App\Http\Requests\JsonApiRequest|null  $request
     * @param  \Vault\Filtering\Queries|null  $queries Queries repository implementation
     */
    public function __construct(JsonApiRequest $request = null, Queries $queries = null)
    {
        $this->request = $request ?: request();
        $this->queries = $queries ?: resolve(Queries::class);
    }

    /**
     * Apply filters and sorting to the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($query)
    {
        foreach ($this->request->filters() as $filter) {
            $this->applyFilter($query, $filter);
        }

        $sorting = $this->request->sorting();
        if ($sorting->valid() && in_array($sorting->column(), $this->sortable)) {
            $query->orderBy($sorting->column(), $sorting->order());
        }

        return $query;
    }

    /**
     * Filter by identifier(s).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterId($query, FilterParameters $filter)
    {
        $ids = array_map(function ($id) {
            return Model::encodeUuid($id);
        }, $filter->valueToArray());

        return $query->whereIn('id', $ids);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws \App\Exceptions\ResourceNotFoundException
     */
    public function filterQuery($query, FilterParameters $filter)
    {
        $id = Uuid::fromNative($filter->value());

        if (! $this->queries->exists($id)) {
            throw (new ResourceExceptionFactory)
                ->createNotFound('The given query resource is no longer available.');
        }

        foreach ($this->queries->get($id)->filters() as $name => $parameters) {
            $this->applyFilter($query, new FilterParameters($name, $parameters));
        }

        return $query;
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterTag($query, FilterParameters $filter)
    {
        return $query->withAnyTags($filter->value());
    }

    /**
     * Determine if the filter value is valid.
     *
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @return bool
     */
    protected function filterValidationFails(FilterParameters $filter)
    {
        $value = $filter->value();
        $rules = ['value' => $this->rules($filter->name())];

        return validator(compact('value'), $rules)->fails();
    }

    /**
     * Return validation rules that applies to a filter with the given name.
     *
     * @param  string  $filterName
     * @return array|string
     */
    protected function rules($filterName)
    {
        $rules = array_merge($this->globalValidationRules, $this->validationRules);

        return array_get($rules, $filterName, []);
    }

    /**
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\FilterParameters  $filter
     * @throws \BadMethodCallException|\InvalidArgumentException if filter validation fails
     */
    private function applyFilter($query, FilterParameters $filter)
    {
        $this->validateFilter($filter);

        $this->{$filter->method()}($query, $filter);
    }

    /**
     * Validate filter parameters.
     *
     * @param  \App\Models\Filtering\FilterParameters  $filter
     */
    private function validateFilter(FilterParameters $filter)
    {
        if (! method_exists($this, $filter->method())) {
            throw new \BadMethodCallException(
                vsprintf('Resource [%s] can\'t be filtered by [%s].', [
                    'resource' => $filter->relationship() ?: $this->request->path(),
                    'filter' => $filter->name(),
                ])
            );
        }
        if ($this->filterValidationFails($filter)) {
            throw new \InvalidArgumentException(
                sprintf('Value for the filter [%s] is not valid.', $filter->name())
            );
        }
    }
}
