<?php

namespace App\Models;

/**
 * @property string $street
 * @property string $postcode
 * @property string $city
 * @property string $state
 * @property string $country
 */
class Address extends Model
{
    use Consentable, Contactable, Filterable;

    /** @var array */
    protected $fillable = ['street', 'city', 'postcode', 'state', 'country', 'tags'];
}
