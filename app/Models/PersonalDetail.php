<?php

namespace App\Models;

/**
 * @property string $person_id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $maiden_name
 * @property \Carbon\Carbon $date_of_birth
 * @property int $sex
 * @property int $marital_status
 */
class PersonalDetail extends Model
{
    use Consentable, Contactable, Filterable;

    protected static $consentRelation = 'people.consents';

    protected $dates = ['date_of_birth'];

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'maiden_name',
        'date_of_birth',
        'sex',
        'marital_status',
        'tags',
    ];

    protected $uuids = ['id', 'person_id'];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id');
    }
}
