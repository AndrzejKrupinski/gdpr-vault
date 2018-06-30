<?php

namespace App\Models\Scopes;

use App\Models\Person;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AssociatedWithPersonScope implements Scope
{
    /** @var string */
    protected $personUuid;

    /**
     * @param  string  $personUuid
     */
    public function __construct(string $personUuid = null)
    {
        if ($personUuid !== null) {
            $this->personUuid = Person::encodeUuid($personUuid);
        }
    }

    /**
     * @inheritdoc
     * @throws \LogicException if the required relationship is not defined
     */
    public function apply(Builder $builder, Model $model)
    {
        if (!$relationship = $this->determineRelationship($model)) {
            throw new \LogicException('The model must define a relationship to a person.');
        }

        if ($this->personUuid) {
            $builder->whereHas($relationship, function (Builder $query) {
                $query->where((new Person)->getQualifiedKeyName(), $this->personUuid);
            });
        } else {
            $builder->has($relationship);
        }
    }

    /**
     * @param  Model  $model
     * @return null|string
     */
    protected function determineRelationship(Model $model): ?string
    {
        return array_first(['person', 'people'], function ($relationship) use ($model) {
            return method_exists($model, $relationship);
        });
    }
}
