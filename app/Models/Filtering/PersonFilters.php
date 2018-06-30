<?php

namespace App\Models\Filtering;

class PersonFilters extends QueryFilters
{
    protected $validationRules = [
        'site' => 'numbers',
    ];

    /**
     * Filter by site(s).
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterSite($query, FilterParameters $filter)
    {
        return $query->whereIn('site', $filter->valueToArray());
    }
}
