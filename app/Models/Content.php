<?php

namespace App\Models;

/**
 * @property string $name
 * @property string $description
 * @property mixed $consents
 */
class Content extends Model
{
    use Filterable;

    /** @var array */
    protected $fillable = ['name', 'description', 'tags'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function consents()
    {
        return $this->hasMany(Consent::class);
    }
}
