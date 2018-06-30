<?php

namespace App\Models;

/**
 * @property string $number
 */
class Phone extends Model
{
    use Consentable, Contactable, Filterable;

    protected $fillable = ['number', 'tags'];
}
