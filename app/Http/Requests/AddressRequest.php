<?php

namespace App\Http\Requests;

use App\Http\Requests\CommonRules\PersonRelationRules;
use App\Http\Requests\CommonRules\TagRules;

class AddressRequest extends JsonApiRequest
{
    use PersonRelationRules, TagRules;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function addressRules()
    {
        return [
            'data.type' => 'in:addresses',
            'data.attributes.*' => 'string|max:255',
        ];
    }
}
