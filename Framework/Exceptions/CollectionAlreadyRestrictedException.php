<?php declare(strict_types=1);

namespace Framework\Exceptions;

use Exception;
use Throwable;

class CollectionAlreadyRestrictedException extends Exception
{
    function __construct(string $message = "Type of Collection has been already defined", int $code = 10100, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}