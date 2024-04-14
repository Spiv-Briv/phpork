<?php declare(strict_types=1);

namespace Framework\Exceptions;

use Exception;
use Throwable;

class CastException extends Exception {
    function __construct(string $message = "Variable couldn't be converted", int $code = 10000, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}