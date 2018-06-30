<?php

namespace App\Models;

/**
 * @property string $description
 * @property mixed $consents
 */
class Purpose extends Model
{
    use Filterable;

    /** @var array */
    protected $fillable = ['description', 'tags'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function consents()
    {
        return $this->belongsToMany(Consent::class)->using(ConsentPurpose::class);
    }
}
