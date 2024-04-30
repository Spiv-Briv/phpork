<?php declare(strict_types=1);

namespace Framework\Exceptions;

use Exception;
use Throwable;

class NotBooleanException extends Exception {
    function __construct(string $message = "Variable is not boolean", int $code = 10011, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}