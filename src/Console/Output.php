<?php

namespace Lilashih\Simphp\Console;

trait Output
{
    public static function print($string = '')
    {
        echo "{$string} \033[0m".PHP_EOL;
    }

    public static function printRed($string = '')
    {
        self::print("\033[31m {$string}");
    }

    public static function printGreen($string = '')
    {
        self::print("\033[32m {$string}");
    }

    public static function printYellow($string = '')
    {
        self::print("\033[33m {$string}");
    }

    public static function printBlue($string = '')
    {
        self::print("\e[96m {$string}");
    }
}
