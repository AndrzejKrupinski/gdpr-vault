<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TransformsRequest;

class ConvertBooleanStrings extends TransformsRequest
{
    protected function transform($key, $value)
    {
        return $this->isBooleanString($value)
            ? filter_var($value, FILTER_VALIDATE_BOOLEAN)
            : $value;
    }

    /**
     * Determine whether the given value is a boolean string.
     *
     * @param  mixed  $value
     * @return bool
     */
    protected function isBooleanString($value)
    {
        return in_array(strtolower($value), ['true', 'false']);
    }
}
