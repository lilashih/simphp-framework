<?php

namespace Lilashih\Simphp\Log;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Lilashih\Simphp\Exception\LogException;

class Log
{
    public const CHANNEL = 'local';

    /**
     * Add message to log file
     *
     * @param string $message       [title]
     * @param array $context        [data]
     * @param string $level         [level]
     * @param string $fileName      [filename]
     *
     * @return void
     */
    public static function add(string $message, array $context = [], $level = 'info', $fileName = null)
    {
        $message = str_replace(" ", "_", $message);
        $file = self::getFile($fileName);

        $formatter = new LineFormatter(null, null, false, true); // 修改log格式，拿掉extra
        $stream = new StreamHandler($file, Logger::DEBUG, true, 0777);
        $stream->setFormatter($formatter);

        $log = new Logger(self::CHANNEL);
        $log->pushHandler($stream);

        if (!is_callable([$log, $level])) {
            throw new LogException('Level of log is not defined');
        }

        $log->{$level}($message, $context);
    }

    protected static function getFile($name = null)
    {
        $name = $name ?? date('Ymd');
        return project_root("/storage/logs/{$name}.log");
    }
}
