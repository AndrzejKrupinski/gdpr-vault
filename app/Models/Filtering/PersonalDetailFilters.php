<?php

namespace App\Models\Filtering;

class PersonalDetailFilters extends QueryFilters
{
    use Searchable;

    /** @var string Name of the search index */
    protected $index = 'personal_details_index';

    /**
     * Filter by first name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterFirstName($query, FilterParameters $filter)
    {
        $matches = $this->match($this->index, 'first_name', $filter->value());

        return $query->whereIn('id_search', $matches);
    }

    /**
     * Filter by last name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterLastName($query, FilterParameters $filter)
    {
        $matches = $this->match($this->index, 'last_name', $filter->value());

        return $query->whereIn('id_search', $matches);
    }

    /**
     * Filter by full name.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  FilterParameters  $filter
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterFullName($query, FilterParameters $filter)
    {
        $columns = ['first_name', 'middle_name', 'last_name', 'maiden_name'];
        $matches = $this->match($this->index, $columns, $filter->value());

        return $query->whereIn('id_search', $matches);
    }
}
