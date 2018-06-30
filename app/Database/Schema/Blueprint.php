<?php

namespace App\Database\Schema;

use Illuminate\Database\Schema\Blueprint as BaseBlueprint;

class Blueprint extends BaseBlueprint
{
    /**
     * Create a new binary column on the table.
     *
     * @param  string  $column
     * @param  int  $length
     * @return \Illuminate\Support\Fluent
     */
    public function binary($column, $length = 255)
    {
        return $this->addColumn('binary', $column, compact('length'));
    }

    /**
     * Create a new uuid column on the table.
     *
     * @param  string  $column
     * @param  bool  $optimized
     * @return \Illuminate\Support\Fluent
     */
    public function uuid($column, $optimized = true)
    {
        if ($optimized) {
            return static::binary($column, 16);
        }

        return parent::uuid($column);
    }

    /**
     * Specify a unique index for the table.
     *
     * @param  string|array  $columns
     * @param  string  $name
     * @param  int  $length
     * @return \Illuminate\Support\Fluent
     */
    public function unique($columns, $name = null, $length = null)
    {
        return $this->indexCommand('unique', $columns, $name, $length);
    }

    /**
     * Specify an index for the table.
     *
     * @param  string|array  $columns
     * @param  string  $name
     * @param  int  $length
     * @return \Illuminate\Support\Fluent
     */
    public function index($columns, $name = null, $length = null)
    {
        return $this->indexCommand('index', $columns, $name, $length);
    }

    /**
     * Add a new index command to the blueprint.
     *
     * @param  string  $type
     * @param  string|array  $columns
     * @param  string|null  $index
     * @param  int  $length
     * @return \Illuminate\Support\Fluent
     */
    protected function indexCommand($type, $columns, $index, $length = null)
    {
        $columns = (array) $columns;

        // If no name was specified for this index, we will create one using a basic
        // convention of the table name, followed by the columns, followed by an
        // index type, such as primary or index, which makes the index unique.
        if (is_null($index)) {
            $index = $this->createIndexName($type, $columns);
        }

        return $this->addCommand($type, compact('index', 'columns', 'length'));
    }
}
