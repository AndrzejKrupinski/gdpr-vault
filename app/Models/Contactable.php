<?php

namespace App\Models;

use App\Models\Scopes\AssociatedWithPersonScope;

trait Contactable
{
    /**
     * Register a new global scope on the model.
     */
    protected static function bootContactable()
    {
        parent::addGlobalScope(new AssociatedWithPersonScope);
    }

    /**
     * Alias for person().
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function people()
    {
        return $this->person();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function person()
    {
        return $this->morphToMany(Person::class, 'contactable');
    }
}
