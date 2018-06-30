<?php

namespace App\Models\Filtering;

class EmailFilters extends QueryFilters
{
    use Searchable;

    /**
     * Filter by address.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterAddress($query, $filter)
    {
        $matches = $this->match('emails_index', 'address', $filter->value());

        return $query->whereIn('id_search', $matches);
    }
}
