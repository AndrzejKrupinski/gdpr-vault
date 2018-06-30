<?php

namespace App\Models;

use App\Models\Scopes\AssociatedWithConsentScope;

trait Consentable
{
    /**
     * Register a new global scope on the model.
     */
    protected static function bootConsentable()
    {
        parent::addGlobalScope(
            new AssociatedWithConsentScope(self::$consentRelation ?? 'consents')
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function consents()
    {
        return $this->morphToMany(Consent::class, 'consentable');
    }
}
