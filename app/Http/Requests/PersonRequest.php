<?php

namespace App\Http\Requests;

class PersonRequest extends JsonApiRequest
{
    public function rules()
    {
        return [
            'data.type' => 'in:people',
            'data.attributes.site' => 'integer|required|between:0,16777215',
        ];
    }

    /**
     * Return list of sites available for the user making the request.
     *
     * @return string
     */
    protected function getAvailableSites()
    {
        return implode(',', optional($this->user())->availableSites() ?: []);
    }

    /**
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     */
    protected function withValidator($validator)
    {
        if ($availableSites = $this->getAvailableSites()) {
            $validator->sometimes('data.attributes.site', "in:$availableSites", function () {
                return auth()->check();
            });
        }
    }
}
