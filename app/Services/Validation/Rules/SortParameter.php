<?php

namespace App\Services\Validation\Rules;

class SortParameter extends PatternRule
{
    /** @string */
    protected $pattern = '/^[\-]?[a-z_]+$/';

    public function message()
    {
        return trans('validation.parameters.sort');
    }
}
