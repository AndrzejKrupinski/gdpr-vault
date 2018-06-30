<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class InvalidJsonPayloadException extends JsonApiException
{
    public function __construct($detail)
    {
        parent::__construct(
            Response::HTTP_FORBIDDEN,
            $this->translator()->trans('exceptions.jsonapi.invalid_payload'),
            $detail
        );
    }
}
