<?php

namespace App\Http\Transformers;

class PersonaldetailTransformer extends EloquentTransformer
{
    protected $availableIncludes = [
        'people',
    ];

    public function __construct()
    {
        $this->appendHiddenAttributes('person_id');
    }
}
