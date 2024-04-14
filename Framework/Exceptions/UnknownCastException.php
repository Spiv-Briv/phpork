<?php declare(strict_types=1);

namespace Framework\Exceptions;

use Exception;
use Throwable;

class UnknownCastException extends Exception {
    function __construct(string $message = "Cast doen't exist", int $code = 11000, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}