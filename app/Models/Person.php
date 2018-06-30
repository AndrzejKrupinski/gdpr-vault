<?php

namespace App\Models;

use App\Models\Scopes\RelatedToAvailableSiteScope;

/**
 * @property int $site
 * @property mixed $consents
 */
class Person extends Model
{
    use Filterable;

    /** @var array */
    protected $fillable = ['site', 'tags'];

    protected static function boot()
    {
        parent::boot();
        parent::addGlobalScope(new RelatedToAvailableSiteScope);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function addresses()
    {
        return $this->morphedByMany(Address::class, 'contactable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consents()
    {
        return $this->hasMany(Consent::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function emails()
    {
        return $this->morphedByMany(Email::class, 'contactable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function personalDetails()
    {
        return $this->hasOne(PersonalDetail::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function phones()
    {
        return $this->morphedByMany(Phone::class, 'contactable');
    }
}
