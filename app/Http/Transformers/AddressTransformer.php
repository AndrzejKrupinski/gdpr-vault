<?php

namespace App\Http\Transformers;

class AddressTransformer extends EloquentTransformer
{
    protected $availableIncludes = [
        'consents',
        'people',
    ];
}
