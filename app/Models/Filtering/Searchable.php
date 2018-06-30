<?php

namespace App\Models\Filtering;

use App\Support\Facades\Sphinx;

trait Searchable
{
    /**
     * @param  array|string  $index
     * @param  mixed  $column
     * @param  mixed  $value
     * @return array
     */
    protected function match($index, $column, $value)
    {
        $query = Sphinx::select('id')->from($index)->match($column, $value);

        return array_pluck($query->execute(), 'id');
    }
}
