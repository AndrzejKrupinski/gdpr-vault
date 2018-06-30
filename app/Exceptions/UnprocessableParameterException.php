<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class UnprocessableParameterException extends JsonApiException
{
    public function __construct($detail)
    {
        parent::__construct(
            Response::HTTP_BAD_REQUEST,
            $this->translator()->trans('exceptions.jsonapi.unprocessable_parameter'),
            $detail
        );
    }
}
