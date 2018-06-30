<?php

namespace App\Services\Validation\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class UuidNotExists extends UuidExists
{
    public function passes($attribute, $value)
    {
        return parent::passes($attribute, $value) === false;
    }

    public function message()
    {
        return trans('validation.uuid_not_exists');
    }
}
