<?php

namespace App\Services\DataObfuscator;

use App\Services\DataObfuscator\Rules\Obfuscate;
use Illuminate\Database\Eloquent\Model;

class DataObfuscator
{
    /** @var Model */
    protected $model;

    /** @var array */
    protected $relations;

    /** @var Obfuscate */
    protected $rule;

    /**
     * @param  Model  $model
     * @param  array  $relations
     * @param  Obfuscate|null  $rule
     */
    public static function obfuscate(Model $model, array $relations = [], Obfuscate $rule = null)
    {
        (new static($model, $relations, $rule))->obfuscateRelations();
    }

    /**
     * @param  Model  $model
     * @param  array  $relations
     * @param  Obfuscate|null  $rule
     */
    private function __construct(Model $model, array $relations = [], Obfuscate $rule = null)
    {
        $this->model = $model;
        $this->relations = $relations;
        $this->rule = $rule;
    }

    public function obfuscateRelations()
    {
        array_walk($this->relations, [$this, 'obfuscateRelation']);
    }

    /**
     * @param  string  $relation
     */
    public function obfuscateRelation($relation)
    {
        $this->model->load([$relation => function ($query) {
            $query->get()->each(function (Model $model) {
                $this->rule ? $this->applyRule($model) : $model->delete();
            });
        }]);
    }

    /**
     * Shred model's attributes.
     *
     * @param  Model  $model
     */
    protected function applyRule(Model $model)
    {
        $this->rule->shredModelAttributes($model);
    }
}
