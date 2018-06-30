<?php

namespace App\Support\Facades;

use App\Database\Grammars\MySqlGrammar;
use App\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Builder;
use Illuminate\Support\Facades\Schema as BaseSchema;

class Schema extends BaseSchema
{
    /**
     * Get a schema builder instance for a connection.
     *
     * @param  string  $name
     * @return \Illuminate\Database\Schema\Builder
     */
    public static function connection($name)
    {
        $connection = static::$app['db']->connection($name);

        return static::useCustomGrammar($connection);
    }

    /**
     * Get a schema builder.
     *
     * @return \Illuminate\Database\Schema\Builder
     */
    protected static function getFacadeAccessor()
    {
        $connection = static::$app['db']->connection();

        return static::useCustomGrammar($connection);
    }

    /**
     * Boot system by calling our custom Grammar
     *
     * @param  \Illuminate\Database\Connection  $connection
     * @return \Illuminate\Database\Schema\Builder
     */
    protected static function useCustomGrammar($connection)
    {
        if (get_class($connection) === 'Illuminate\Database\MySqlConnection') {
            $mySqlGrammar = $connection->withTablePrefix(new MySqlGrammar);
            $connection->setSchemaGrammar($mySqlGrammar);
        }

        return tap($connection->getSchemaBuilder(), function (Builder $schema) {
            $schema->blueprintResolver(function ($table, $callback) {
                return new Blueprint($table, $callback);
            });
        });
    }
}
