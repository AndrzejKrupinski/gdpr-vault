<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class JsonApiException extends HttpException
{
    /** @var string|null */
    protected $detail;

    /**
     * @param  int|string  $status The HTTP status code applicable to this problem, expressed as a string value
     * @param  string  $title A short, human-readable summary of the problem that SHOULD NOT change
     * @param  string|null  $detail A human-readable explanation specific to this occurrence of the problem
     * @param  int|string|null  $code An application-specific error code, expressed as a string value
     */
    public function __construct($status, $title, $detail = null, $code = null)
    {
        parent::__construct((int) $status, $title, null, [], (int) $code);

        $this->detail = $detail;
    }

    public function getDetail()
    {
        return $this->detail;
    }

    /**
     * @return \Illuminate\Contracts\Translation\Translator
     */
    protected function translator()
    {
        return optional(app()->has('translator') ? app('translator') : null);
    }
}
