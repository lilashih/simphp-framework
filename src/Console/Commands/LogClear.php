<?php

namespace Lilashih\Simphp\Console\Commands;

class LogClear extends BaseCommand
{
    public function handel()
    {
        $path = project_root('/storage/logs/*.log');

        array_map('unlink', array_filter((array)glob($path)));

        static::printGreen('Delete successfully');
    }
}
