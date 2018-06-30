<?php

namespace App\Http\Requests;

use App\Http\Requests\CommonRules\PersonRelationRules;

class PersonalDetailRequest extends JsonApiRequest
{
    use PersonRelationRules;

    public function personalDetailRules()
    {
        return [
            'data.type' => 'in:personaldetails',
            'data.attributes.date_of_birth' => 'shortdate',
        ];
    }
}
