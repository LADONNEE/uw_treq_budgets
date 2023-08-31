<?php

namespace App\Logging;

use Illuminate\Log\LogManager;
use Monolog\Logger as MonologLogger;
use Psr\Log\LoggerInterface;

/**
 * Support tool for FlexLogStack
 * Examines a log object and returns an array description of the configuration
 */
class ExamineLog
{
    public static function describe($log = null): array
    {
        if (!$log instanceof LoggerInterface) {
            $log = app(LoggerInterface::class);
        }

        return ($log instanceof MonologLogger || $log instanceof LogManager)
            ? self::describeStack($log)
            : self::describeSingle($log);
    }

    private static function describeStack($log): array
    {
        $out = [
            'classname' => get_class($log),
            'handlers' => []
        ];;
        foreach ($log->getHandlers() as $handler) {
            $out['handlers'][] = [
                'classname' => get_class($handler),
                'logfile' => method_exists($handler, 'getUrl') ? $handler->getUrl() : '',
                'level' => self::getLevelName($handler),
            ];
        }

        return $out;
    }

    private static function describeSingle($log): array
    {
        return [
            'classname' => get_class($log)
        ];
    }

    private static function getLevelName($handler): string
    {
        if (!method_exists($handler, 'getLevel')) {
            return '';
        }

        return MonologLogger::getLevelName($handler->getLevel());
    }
}
