<?php

namespace App\Http\Response\Format;

use Dingo\Api\Http\Response\Format\Json as JsonFormat;
use Illuminate\Http\Request;

class Json extends JsonFormat
{
    /** @var string */
    const MEDIA_TYPE_FORMAT = 'application/%s.api+json';

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getContentType()
    {
        return sprintf(static::MEDIA_TYPE_FORMAT, config('api.standardsTree'));
    }

    /**
     * Check if a request's Accept header contains the JSON API media type
     * and there is at least one instance of that media type that is not modified with media type parameters.
     *
     * @return bool
     */
    public function validateAccept()
    {
        $acceptableTypes = explode(',', $this->request->header('Accept'));

        return in_array($this->getContentType(), $acceptableTypes);
    }

    /**
     * Check if the request specifies a valid media type within the Content-Type header.
     *
     * @return bool
     */
    public function validateContentType()
    {
        return Request::matchesType(
            $this->request->header('Content-Type'),
            $this->getContentType()
        );
    }
}
