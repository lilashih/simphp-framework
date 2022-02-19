<?php

namespace Lilashih\Simphp\Exception;

use Exception;
use Throwable;

class ModelNotFoundException extends Exception
{
    public function __construct(string $message = 'Model not found', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
