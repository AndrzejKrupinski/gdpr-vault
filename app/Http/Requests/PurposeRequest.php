<?php

namespace App\Http\Requests;

class PurposeRequest extends JsonApiRequest
{
    public function rules()
    {
        return [
            'data.type' => 'in:purposes',
            'data.attributes.description' => 'string|max:255|required',
        ];
    }
}
