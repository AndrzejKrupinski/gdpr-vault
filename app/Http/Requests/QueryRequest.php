<?php

namespace App\Http\Requests;

class QueryRequest extends JsonApiRequest
{
    /**
     * Retrieve the filters item from the request.
     *
     * @return array
     */
    public function filters()
    {
        return $this->input('data.attributes.filters', []);
    }

    public function rules()
    {
        return [
            'data.type' => 'in:queries',
            'data.attributes.filters' => 'array|required',
        ];
    }
}
