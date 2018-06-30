<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class ConflictedResourceStateException extends JsonApiException
{
    public function __construct($detail)
    {
        parent::__construct(
            Response::HTTP_CONFLICT,
            $this->translator()->trans('exceptions.jsonapi.request_conflicts_with_request'),
            $detail
        );
    }
}
