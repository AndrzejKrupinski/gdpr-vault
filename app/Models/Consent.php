<?php

namespace App\Models;

use App\Models\Scopes\AssociatedWithPersonScope;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $content_id
 * @property string $meta
 * @property string $person_id
 * @property bool $confirmed
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property Carbon $expired_at
 * @property Carbon $deleted_at
 */
class Consent extends Model
{
    use Filterable, SoftDeletes;

    /** @var string The name of the "expired at" column. */
    const EXPIRED_AT = 'expired_at';

    protected $casts = ['confirmed' => 'bool', 'meta' => 'array'];

    protected $fillable = ['person_id', 'content_id', 'confirmed', 'meta', 'tags', self::EXPIRED_AT];

    protected $uuids = ['id', 'person_id', 'content_id'];

    protected $visible = ['confirmed', 'expired_at'];

    protected static function boot()
    {
        parent::boot();
        parent::addGlobalScope(new AssociatedWithPersonScope);
        parent::addGlobalScope('unexpired', function (Builder $builder) {
            $builder->whereDate(static::EXPIRED_AT, '>', now()->toDateString());
        });
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function addresses()
    {
        return $this->morphedByMany(Address::class, 'consentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contents()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function emails()
    {
        return $this->morphedByMany(Email::class, 'consentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function people()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }

    /**
     * Alias for people()
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function person()
    {
        return $this->people();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function phones()
    {
        return $this->morphedByMany(Phone::class, 'consentable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function purposes()
    {
        return $this->belongsToMany(Purpose::class);
    }

    /**
     * @param  Builder  $builder
     * @param  Carbon|null  $after
     * @return Builder
     */
    public function scopeExpired($builder, Carbon $after = null)
    {
        $after = Carbon::parse($after);

        return $builder->withoutGlobalScope('unexpired')
            ->whereDate(static::EXPIRED_AT, '<=', $after->toDateString());
    }

    /**
     * Determine if the consent is confirmed.
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return $this->getAttribute('confirmed') === true;
    }
}
