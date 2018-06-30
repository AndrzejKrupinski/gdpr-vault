<?php

namespace App\Models\Filtering;

class PhoneFilters extends QueryFilters
{
    use Searchable;

    /**
     * Filter by number.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterNumber($query, FilterParameters $filter)
    {
        $matches = $this->match('phones_index', 'number', $filter->value());

        return $query->whereIn('id_search', $matches);
    }
}
