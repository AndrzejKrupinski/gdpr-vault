<?php

namespace App\Models;

trait HasBinaryUuid
{
    use \Spatie\BinaryUuid\HasBinaryUuid;

    /** @var array The attributes that are stored as binary uuids. */
    protected $uuids = ['id'];

    /**
     * This methods discards trait method which overrides primary key
     * setting it value arbitrarily to 'uuid'. It uses model's pk.
     *
     * @return string
     */
    public function getKeyName()
    {
        return parent::getKeyName();
    }

    /**
     * Return decoded values of all non-empty uuid attributes.
     *
     * @return array
     */
    public function getUuidAttributes()
    {
        return collect($this->uuids)->filter(function ($field) {
            return $this->getAttribute($field) !== null;
        })->mapWithKeys(function ($field) {
            return [$field => static::decodeUuid($this->getAttribute($field))];
        })->toArray();
    }

    /**
     * Determine if the given attribute is an uuid.
     *
     * @param  string  $key
     * @return bool
     */
    protected function isUuidAttribute($key)
    {
        return in_array($key, $this->uuids);
    }
}
