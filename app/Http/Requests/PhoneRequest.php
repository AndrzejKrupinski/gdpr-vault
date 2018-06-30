<?php

namespace App\Http\Requests;

use App\Http\Requests\CommonRules\PersonRelationRules;

class PhoneRequest extends JsonApiRequest
{
    use PersonRelationRules;

    public function phoneRules()
    {
        return [
            'data.type' => 'in:phones',
            'data.attributes.number' => 'string|required',
        ];
    }
}
