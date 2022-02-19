<?php

namespace Lilashih\Simphp\Exception;

use Exception;
use Throwable;

class PageNotFoundException extends Exception
{
    public function __construct(string $message = 'Page not found', int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}