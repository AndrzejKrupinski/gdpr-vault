<?php

namespace App\Http\Transformers;

class ConsentTransformer extends EloquentTransformer
{
    protected $availableIncludes = [
        'addresses',
        'contents',
        'emails',
        'people',
        'phones',
        'purposes',
    ];

    public function __construct()
    {
        $this->appendHiddenAttributes(['content_id', 'person_id']);
    }
}
