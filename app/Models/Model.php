<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

/**
 * @todo Create custom trait Auditable
 *
 * @property string $id
 * @property string $uuid_text  The binary uuid field as string with dashes
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
abstract class Model extends BaseModel implements AuditableContract
{
    use HasTags, HasBinaryUuid, Auditable {
        transformAudit as traitTransformAudit;
    }

    protected $appends = ['tags'];

    /**
     * @todo toArray method uses this one and sets exactly the same value pairs (pk => uuid)
     * @inheritdoc
     */
    public function attributesToArray()
    {
        return array_merge(parent::attributesToArray(), $this->getUuidAttributes());
    }

    public function setAttribute($key, $value)
    {
        if ($this->isUuidAttribute($key) && $value !== null) {
            $value = static::encodeUuid($value);
        }

        parent::setAttribute($key, $value);
    }

    /**
     * Converts all binary uuid keys to string representation before saving in audit table.
     * This is a must since json don't accept binary values.
     *
     * @param  array  $data
     * @return array
     */
    public function transformAudit(array $data): array
    {
        foreach (['old_values', 'new_values'] as $json) {
            array_walk_recursive($data[$json], function (&$value, $key) {
                if ($this->isUuidAttribute($key) && $value !== null) {
                    $value = static::decodeUuid($value);
                }
            });
        }

        return $data;
    }
}
