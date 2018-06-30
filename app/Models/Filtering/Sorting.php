<?php

namespace App\Models\Filtering;

class Sorting
{
    /** @var string|null */
    protected $column;

    /** @var string */
    protected $order;

    public function __construct(string $sort)
    {
        $this->column = ltrim($sort, '-+') ?: null;
        $this->order = strpos($sort, '-') === 0 ? 'desc' : 'asc';
    }

    /**
     * @return null|string
     */
    public function column()
    {
        return $this->column;
    }

    /**
     * @return string
     */
    public function order()
    {
        return $this->order;
    }

    /**
     * Check whether sorting parameters have been parsed successfully.
     *
     * @return bool
     */
    public function valid()
    {
        return $this->column !== null;
    }
}
