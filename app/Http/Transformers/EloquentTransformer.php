<?php

namespace App\Http\Transformers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use League\Fractal\TransformerAbstract;

class EloquentTransformer extends TransformerAbstract
{
    /** @var array The attributes that should be hidden for transformation. */
    protected $hiddenAttributes = [
        'id_search',
        'created_at',
        'updated_at',
    ];

    /**
     * Create the transformer using a resource name.
     *
     * @param  string  $resource
     * @return TransformerAbstract
     */
    protected static function resolveTransformer($resource)
    {
        $transformer = Str::replaceFirst('Eloquent', Str::ucfirst(Str::singular($resource)), __CLASS__);

        if (class_exists($transformer)) {
            return new $transformer;
        }

        return new self;
    }

    /**
     * @param  array|string  $attributes
     * @return self
     */
    public function appendHiddenAttributes($attributes)
    {
        foreach ((array) $attributes as $attribute) {
            $this->hiddenAttributes[] = $attribute;
        }

        return $this;
    }

    /**
     * Return hidden attributes.
     *
     * @return array
     */
    public function hiddenAttributes()
    {
        return $this->hiddenAttributes;
    }

    /**
     * @param  array  $attributes
     * @return self
     */
    public function setHiddenAttributes($attributes)
    {
        $this->hiddenAttributes = (array) $attributes;

        return $this;
    }

    /**
     * @param  Model  $model
     * @return array
     */
    public function transform($model)
    {
        return array_except($model->attributesToArray(), $this->hiddenAttributes);
    }

    public function __call($name, $arguments)
    {
        if (Str::startsWith($name, 'include')) {
            $resource = Str::after($name, 'include');

            return $this->includeResourceAutomatically($resource, ...$arguments);
        }

        throw new \BadMethodCallException(
            sprintf('Call to undefined method [%s] on transformer [%s].', $name, static::class)
        );
    }

    /**
     * @param  string  $resource
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \League\Fractal\ParamBag  $parameters
     * @return \League\Fractal\Resource\ResourceAbstract|null
     */
    protected function includeResourceAutomatically($resource, $model, $parameters)
    {
        $relation = $model->getRelationValue($resource);
        $arguments = [$relation, static::resolveTransformer($resource), Str::lower($resource)];

        if ($relation instanceof Collection) {
            return $this->collection(...$arguments);
        } elseif ($relation instanceof Model) {
            return $this->item(...$arguments);
        }
    }
}
