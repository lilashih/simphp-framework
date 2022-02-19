<?php

namespace Lilashih\Simphp\Exception;

use Exception;
use Throwable;

class ValidationException extends Exception
{
    protected $data = [];

    public function __construct(array $data, string $message = '', int $code = 0, Throwable $previous = null)
    {
        $this->data = $data;

        parent::__construct($message, $code, $previous);
    }

    public function getData()
    {
        return $this->data;
    }
}
