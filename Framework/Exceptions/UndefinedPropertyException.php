<?php declare(strict_types=1);

namespace Framework\Exceptions;

use Exception;
use Throwable;

class UndefinedPropertyException extends Exception {
    function __construct(string $message = "Property doesn't exist", int $code = 12000, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}