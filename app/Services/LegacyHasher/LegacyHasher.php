<?php

namespace App\Services\LegacyHasher;

use Illuminate\Contracts\Hashing\Hasher;

class LegacyHasher implements Hasher
{
    /**
     * @param  string  $hashedValue
     * @return array
     */
    public function info($hashedValue)
    {
        return [];
    }

    /**
     * @param  string  $value
     * @param  array  $options
     * @return string
     */
    public function make($value, array $options = [])
    {
        return hash('sha256', $value . config('auth.legacy.hashing_salt'));
    }

    /**
     * @param  string  $value
     * @param  string  $hashedValue
     * @param  array  $options
     * @return bool
     */
    public function check($value, $hashedValue, array $options = [])
    {
        return $this->make($value) === $hashedValue;
    }

    /**
     * @param  string  $hashedValue
     * @param  array  $options
     * @return false
     */
    public function needsRehash($hashedValue, array $options = [])
    {
        return false;
    }
}
