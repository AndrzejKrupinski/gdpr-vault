<?php

namespace Vault\Exceptions;

class RepositoryException extends \RuntimeException
{
    public function __construct(string $message, \Throwable $previous = null)
    {
        parent::__construct($message, null, $previous);
    }
}
