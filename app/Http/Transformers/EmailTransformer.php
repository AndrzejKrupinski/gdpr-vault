<?php

namespace App\Http\Transformers;

class EmailTransformer extends EloquentTransformer
{
    protected $availableIncludes = [
        'consents',
        'people',
    ];
}
