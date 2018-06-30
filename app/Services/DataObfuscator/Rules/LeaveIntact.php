<?php

namespace App\Services\DataObfuscator\Rules;

/**
 * Just a dummy rule that does nothing.
 */
class LeaveIntact extends Obfuscate
{
    /**
     * @param  string  $value
     * @return mixed
     */
    public static function obfuscate($value)
    {
        return $value;
    }
}
