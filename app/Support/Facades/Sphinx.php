<?php

namespace App\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Foolz\SphinxQL\SphinxQL select(array|string $columns)
 * @method static string getCompiled()
 *
 * @see \Foolz\SphinxQL\SphinxQL
 */
class Sphinx extends Facade
{
    /**
     * Get the registered name of the Sphinx query builder.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'sphinxql';
    }
}
