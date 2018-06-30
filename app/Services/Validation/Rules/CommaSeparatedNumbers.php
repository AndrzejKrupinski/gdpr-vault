<?php

namespace App\Services\Validation\Rules;

class CommaSeparatedNumbers extends PatternRule
{
    /** @var string */
    protected $pattern = '/^[0-9]+(,[0-9]+)*$/';

    public function message()
    {
        return trans('validation.comma_separated_numbers');
    }
}
