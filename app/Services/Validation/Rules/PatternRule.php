<?php

namespace App\Services\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

abstract class PatternRule implements Rule
{
    /** @var string */
    protected $pattern = '/^.*$/';

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  string  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return preg_match($this->pattern, $value) > 0;
    }

    public function message()
    {
        return trans('validation.regex');
    }
}
