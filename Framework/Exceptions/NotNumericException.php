<?php declare(strict_types=1);

namespace Framework\Exceptions;

use Exception;
use Throwable;

class NotNumericException extends Exception {
    function __construct(string $message = "Variable is not numeric", int $code = 10010, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}