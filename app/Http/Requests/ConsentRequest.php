<?php

namespace App\Http\Requests;

use App\Http\Requests\CommonRules\PersonRelationRules;
use App\Http\Requests\CommonRules\TagRules;
use App\Models\Content;
use App\Services\Validation\Rule;

class ConsentRequest extends JsonApiRequest
{
    use TagRules, PersonRelationRules {
        withValidator as protected addPersonRelationConditions;
    }

    public $relationships = [
        'addresses',
        'contents',
        'emails',
        'people',
        'phones',
        'purposes',
    ];

    public function consentRules()
    {
        return [
            'data.type' => 'in:consents',
            'data.attributes.confirmed' => 'boolean',
            'data.attributes.expired_at' => 'shortdate',
            'data.attributes.meta' => 'array',
            'data.relationships.content.data.id' => Rule::uuidExists(Content::class),
        ];
    }

    /**
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     */
    protected function withValidator($validator)
    {
        tap($validator, function ($validator) {
            $this->addPersonRelationConditions($validator);
        })->sometimes('data.relationships.content.data', 'required', function () {
            return $this->isMethod(self::METHOD_POST);
        });
    }
}
