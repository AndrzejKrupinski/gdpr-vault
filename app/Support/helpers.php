<?php

use Illuminate\Support\Str;

if (!function_exists('str_conceal')) {
    /**
     * @param  string  $string
     * @param  int  $visible
     * @param  string  $character
     * @param  string|null  $pattern
     * @return string
     */
    function str_conceal(string $string, int $visible = 3, string $character = '*', string $pattern = null)
    {
        // \p{L} matches any kind of letter from any language, \p{N} any kind of numeric character in any script
        $pattern = $pattern ?? '/\p{L}|\p{N}/ui';

        return Str::substr($string, 0, $visible) . preg_replace($pattern, $character, Str::substr($string, $visible));
    }
}

if (!function_exists('legacy_hash')) {
    /**
     * @param  string  $string
     * @return string
     */
    function legacy_hash(string $string)
    {
        return app('hash.legacy')->make($string);
    }
}
