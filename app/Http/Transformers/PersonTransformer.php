<?php

namespace App\Http\Transformers;

class PersonTransformer extends EloquentTransformer
{
    protected $availableIncludes = [
        'addresses',
        'consents',
        'emails',
        'personaldetails',
        'phones',
    ];
}
