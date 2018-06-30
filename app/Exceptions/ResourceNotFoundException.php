<?php

namespace App\Exceptions;

use Illuminate\Http\Response;

class ResourceNotFoundException extends JsonApiException
{
    public function __construct(?string $detail = null)
    {
        parent::__construct(
            Response::HTTP_NOT_FOUND,
            $this->translator()->trans('exceptions.jsonapi.resource_not_found'),
            $detail
        );
    }
}
