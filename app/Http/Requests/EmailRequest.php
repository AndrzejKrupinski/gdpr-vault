<?php

namespace App\Http\Requests;

use App\Http\Requests\CommonRules\PersonRelationRules;
use App\Http\Requests\CommonRules\TagRules;

class EmailRequest extends JsonApiRequest
{
    use PersonRelationRules, TagRules;

    public function emailRules()
    {
        return [
            'data.type' => 'in:emails',
            'data.attributes.address' => 'string|required',
        ];
    }
}
