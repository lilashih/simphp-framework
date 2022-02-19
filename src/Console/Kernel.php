<?php

namespace Lilashih\Simphp\Console;

$_SERVER['DOCUMENT_ROOT'] = getcwd() . '/public';
require_once $_SERVER['DOCUMENT_ROOT'] . '/./../bootstrap/app.php';

use Lilashih\Simphp\Console\Commands\Stub;
use Lilashih\Simphp\Console\Commands\Migrate;
use Lilashih\Simphp\Console\Commands\LogClear;
use Composer\Script\Event;
use Composer\Installer\PackageEvent;
use Exception;
use Lilashih\Simphp\Log\Log;

abstract class Kernel
{
    use Output;

    const COMMANDS = [];

    public static function commands($name)
    {
        $command = array_merge([
            'migrate' => Migrate::class,
            'make:curd' => Stub::class,
            'log:clear' => LogClear::class,
        ], static::COMMANDS);

        return $command[$name] ?? null;
    }

    public static function call(Event $event)
    {
        try {
            $args = $event->getArguments();
            $name = array_shift($args);

            if ($command = self::commands($name)) {
                $service = new $command(...$args);
                $service->handel();
            } else {
                throw new Exception("Command '{$name}' is not defined");
            }
        } catch (Exception $e) {
            Log::add("Exec Command Fail:{$name}", [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'message' => $e->getMessage(),
            ]);
            self::printRed("ERROR. {$e->getMessage()} {$e->getFile()} {$e->getLine()} ");
        }
    }
}
