<?php declare(strict_types=1);

namespace Framework\Exceptions;

use Exception;
use Throwable;

class CollectionTypeNotMatchedException extends Exception
{
    function __construct(string $message = "Value doesn't match type of Collection", int $code = 10101, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}