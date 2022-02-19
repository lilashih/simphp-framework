<?php

namespace Lilashih\Simphp\Console\Commands;

use Lilashih\Simphp\Console\Output;

abstract class BaseCommand
{
    use Output;

    abstract public function handel();
}
