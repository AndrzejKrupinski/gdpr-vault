<?php

namespace App\Models;

/**
 * @property string $address
 */
class Email extends Model
{
    use Consentable, Contactable, Filterable;

    protected $fillable = ['address', 'tags'];
}
