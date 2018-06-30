<?php

namespace App\Services\DataObfuscator\Rules;

class Wipe extends Obfuscate
{
    public static function obfuscate($value)
    {
        return '';
    }
}
