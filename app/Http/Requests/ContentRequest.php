<?php

namespace App\Http\Requests;

class ContentRequest extends JsonApiRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'data.type' => 'in:contents',
            'data.attributes.name' => 'string|max:255|required',
            'data.attributes.description' => 'string|required',
        ];
    }
}
