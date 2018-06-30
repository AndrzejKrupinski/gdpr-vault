<?php

namespace App\Http\Transformers;

class ContentTransformer extends EloquentTransformer
{
    protected $availableIncludes = [
        'consents',
    ];
}
