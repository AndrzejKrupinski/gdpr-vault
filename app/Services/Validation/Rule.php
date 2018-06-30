<?php

namespace App\Services\Validation;

class Rule
{
    /**
     * @return \App\Services\Validation\Rules\CommaSeparatedNumbers
     */
    public static function commaSeparatedNumbers()
    {
        return new Rules\CommaSeparatedNumbers;
    }

    /**
     * Similar to uuidNotExists() but uses the resource name instead of model.
     *
     * @param  string  $name
     * @return \App\Services\Validation\Rules\UuidNotExists
     */
    public static function resourceNotExists($name)
    {
        $model = sprintf('%s\%s', 'App\Models', ucfirst(str_singular($name)));

        return static::uuidNotExists($model);
    }

    /**
     * @param  array|string  $types List of valid resource types
     * @return Rules\ResourceRelationship
     */
    public static function resourceRelationship($types)
    {
        return new Rules\ResourceRelationship($types);
    }

    /**
     * @param  string|\Illuminate\Database\Eloquent\Model  $model
     * @param  string  $column
     * @param  bool  $optimize
     * @return Rules\UuidExists
     */
    public static function uuidExists($model, string $column = 'id', bool $optimize = true)
    {
        return new Rules\UuidExists($model, $column, $optimize);
    }

    /**
     * @param  string|\Illuminate\Database\Eloquent\Model  $model
     * @param  string  $column
     * @param  bool  $optimize
     * @return Rules\UuidNotExists
     */
    public static function uuidNotExists($model, string $column = 'id', bool $optimize = true)
    {
        return new Rules\UuidNotExists($model, $column, $optimize);
    }
}
