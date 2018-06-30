<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class AssociatedWithConsentScope implements Scope
{
    /** @var string */
    protected $relation;

    public function __construct($relation = 'consents')
    {
        $this->relation = $relation;
    }

    public function apply(Builder $builder, Model $model)
    {
        $builder->whereHas($this->relation, function (Builder $query) {
            $query->where('confirmed', true);
        });
    }
}
