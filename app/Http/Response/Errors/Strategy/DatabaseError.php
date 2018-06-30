<?php

namespace App\Http\Response\Errors\Strategy;

use Illuminate\Database\QueryException;
use Illuminate\Http\Response;

class DatabaseError extends JsonApiStrategy
{
    /** @var int[] provides exceptions, corresponding to specific HTTP status codes */
    protected $exceptionStatuses = [
        QueryException::class => Response::HTTP_INTERNAL_SERVER_ERROR,
    ];

    public function code(): ?string
    {
        if (!$this->shouldAttachErrorDetails()) {
            return parent::code();
        }

        list ($status, $code) = $this->errorDetails();

        return "$status:$code";
    }

    public function detail(): ?string
    {
        if (!$this->shouldAttachErrorDetails()) {
            return parent::detail();
        }

        list (, , $message) = $this->errorDetails();

        return $message;
    }

    public function title(): string
    {
        return 'A database error occured.';
    }

    private function errorDetails(): ?array
    {
        return object_get($this->exception->getPrevious(), 'errorInfo');
    }

    private function shouldAttachErrorDetails(): bool
    {
        return config('app.debug')
            && $this->exception->getPrevious() instanceof \PDOException;
    }
}
