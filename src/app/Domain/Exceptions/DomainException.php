<?php

namespace App\Domain\Exceptions;

use Throwable;

class DomainException extends \Exception
{
    public function __construct(
        string $message = "Domain error occurred",
        int $code = 422,
        ?Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
