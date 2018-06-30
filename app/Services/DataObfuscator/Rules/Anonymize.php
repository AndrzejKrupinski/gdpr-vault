<?php

namespace App\Services\DataObfuscator\Rules;

class Anonymize extends Obfuscate
{
    public static function obfuscate($value)
    {
        if (!blank($value)) {
            return str_conceal($value);
        }
    }
}
