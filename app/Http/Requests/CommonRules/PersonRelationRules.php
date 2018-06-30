<?php

namespace App\Http\Requests\CommonRules;

use App\Models\Person;
use App\Services\Validation\Rule;

trait PersonRelationRules
{
    /**
     * Return rules specific to a request that stores a resource associated with a person.
     *
     * @return array
     */
    public function personRules()
    {
        return [
            'data.relationships.person.data' => 'array',
            'data.relationships.person.data.type' => 'in:people',
            'data.relationships.person.data.id' => Rule::uuidExists(Person::class),
        ];
    }

    /**
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     */
    protected function withValidator($validator)
    {
        $validator->sometimes('data.relationships.person.data', 'required', function () {
            return $this->isMethod('post');
        });
    }
}
