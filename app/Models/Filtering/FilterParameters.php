<?php

namespace App\Models\Filtering;

class FilterParameters
{
    /** @var string */
    protected $name;

    /** @var mixed */
    protected $value;

    /**
     * @param  string  $name
     * @param  mixed  $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * Return name of a filtering method.
     *
     * @return string
     */
    public function method()
    {
        return camel_case('filter ' . str_replace('.', ' ', $this->name));
    }

    /**
     * Return name of the filter.
     *
     * @return string
     */
    public function name()
    {
        return $this->relationship() ? str_after($this->name, '.') : $this->name;
    }

    /**
     * Return name of the relationship to which filtration applies.
     *
     * @return string|false
     */
    public function relationship()
    {
        return strstr($this->name, '.', true);
    }

    /**
     * Return value of the filter.
     *
     * @return mixed
     */
    public function value()
    {
        return $this->value;
    }

    /**
     * Return an array representation of the filter value.
     *
     * @param  string  $separator
     * @return array
     */
    public function valueToArray(string $separator = ',')
    {
        if (is_array($this->value)) {
            return $this->value;
        }

        return is_string($this->value)
            ? explode($separator, $this->value)
            : (array) $this->value;
    }
}
