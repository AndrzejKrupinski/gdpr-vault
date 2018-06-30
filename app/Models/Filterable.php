<?php

namespace App\Models;

use App\Exceptions\UnprocessableParameterException;
use App\Models\Filtering\QueryFilters;

trait Filterable
{
    /**
     * Filter a result set or throw an exception.
     *
     * @param  \App\Models\Filtering\QueryFilters  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws UnprocessableParameterException if there is an invalid filter
     */
    public static function filterOrFail(QueryFilters $filters)
    {
        try {
            return static::filter($filters);
        } catch (\BadMethodCallException | \InvalidArgumentException $exception) {
            throw new UnprocessableParameterException($exception->getMessage());
        }
    }

    /**
     * Filter a result set.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Filtering\QueryFilters  $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, QueryFilters $filters)
    {
        return $filters->apply($query);
    }
}
