<?php

namespace App\Logging;

use Illuminate\Log\LogManager;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

class AddStdOut
{
    const LOG_FORMAT = "%datetime% %level_name%: %message%\n";
    const DATE_FORMAT = 'H:i:s';

    /**
     * Add standard output to a log's stack
     * Update the default logger if $log argument is not provided
     */
    public static function toStack($log = null)
    {
        if (!$log instanceof LoggerInterface) {
            $log = app(LoggerInterface::class);
        }

        if ($log instanceof MonologLogger || $log instanceof LogManager) {
            $handler = new StreamHandler('php://stdout', MonologLogger::DEBUG);
            $handler->setFormatter(new LineFormatter(self::LOG_FORMAT, self::DATE_FORMAT));
            $log->pushHandler($handler);
        }
    }
}
