<?php

namespace App\Services\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;

class FilterParameter implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!is_array($value)) {
            return false;
        }

        foreach ($value as $k => $v) {
            if (!is_string($k)) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return trans('validation.parameters.filter');
    }
}
