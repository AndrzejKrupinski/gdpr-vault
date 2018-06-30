<?php

namespace App\Http\Transformers;

class PurposeTransformer extends EloquentTransformer
{
    protected $availableIncludes = [
        'consents',
    ];
}
