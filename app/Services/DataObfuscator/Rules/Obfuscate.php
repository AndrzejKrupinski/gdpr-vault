<?php

namespace App\Services\DataObfuscator\Rules;

use Illuminate\Database\Eloquent\Model;

abstract class Obfuscate
{
    /**
     * @param  string  $value
     * @return mixed
     */
    abstract public static function obfuscate($value);

    /** @param  Model  $model */
    public function shredModelAttributes(Model $model): void
    {
        foreach ($this->affectedAttributes($model) as $attribute) {
            $model->{$attribute} = $this->obfuscate($model->{$attribute});
        }
        $model->save();
    }

    /**
     * @param  Model  $model
     * @param  array  $attributes
     * @return array
     */
    public function affectedAttributes(Model $model, array $attributes = []): array
    {
        return !empty($attributes) ? $attributes : $model->getFillable();
    }
}
