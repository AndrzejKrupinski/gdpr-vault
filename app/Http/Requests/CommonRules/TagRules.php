<?php

namespace App\Http\Requests\CommonRules;

trait TagRules
{
    /**
     * @return array
     */
    public function tagRules()
    {
        return [
            'data.attributes.tags' => 'array',
            'data.attributes.tags.*' => 'string',
        ];
    }
}
