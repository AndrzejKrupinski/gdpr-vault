<?php

namespace App\Services\Validation\Rules;

use App\Models\HasBinaryUuid;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UuidExists implements Rule
{
    /** @var string|Model */
    protected $model;

    /** @var string */
    protected $column;

    /** @var bool */
    protected $optimize;

    /**
     * Create a new rule instance.
     *
     * @param  string|Model  $model
     * @param  string  $column
     * @param  bool  $optimize
     */
    public function __construct($model, string $column = 'id', bool $optimize = true)
    {
        if (! $this->isValidModel($model)) {
            throw new \InvalidArgumentException(sprintf(
                'The given model [%s] does not use the HasBinaryUuid.',
                is_object($model) ? get_class($model) : $model
            ));
        }

        $this->model = $model;
        $this->column = $column;
        $this->optimize = $optimize;
    }

    public function passes($attribute, $value)
    {
        if ($this->optimize) {
            $value = $this->optimize($value);
        }

        return $this->query()->where($this->column, $value)->first([$this->column]) !== null;
    }

    public function message()
    {
        return trans('validation.uuid_exists');
    }

    /**
     * Check whether the given model is a valid class.
     *
     * @param  string|object  $model
     * @return bool
     */
    protected function isValidModel($model)
    {
        return array_has(class_uses_recursive($model), HasBinaryUuid::class);
    }

    /**
     * Return optimized uuid.
     *
     * @param  string  $value
     * @return string
     */
    protected function optimize($value)
    {
        return forward_static_call([$this->model, 'encodeUuid'], $value);
    }

    /**
     * @return \Illuminate\Database\Query\Builder
     */
    protected function query()
    {
        return $this->model::query()->withoutGlobalScopes();
    }
}
