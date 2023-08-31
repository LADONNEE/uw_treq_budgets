<?php

namespace App\Logging;

use Illuminate\Contracts\Foundation\Application;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger as MonologLogger;
use Rollbar\Laravel\MonologHandler;
use Rollbar\RollbarLogger;

/**
 * Create a Monolog logging stack that writes to a file and optionally to Rollbar error reporting service
 *
 * Adjust threshold log level for web request logging (debug) and async jobs run via CLI (notice)
 *
 * @see /docs/logging.md
 */
class CreateFlexLogStack
{
    const LOG_FORMAT = "[%datetime%] %channel%.%level_name%: %message%\n";
    const DATE_FORMAT = 'Y-m-d H:i:s';
    const LOG_FILE_FALLBACK = 'logs/laravel.log';

    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Argument $config comes from config/logging.php ['channels']['flex']
     * @param $config
     * @return MonologLogger
     */
    public function __invoke($config)
    {
        // Choose log settings based on environment
        $isCLI = $this->detectCommandLineInterface();
        $fileLogLevel = ($isCLI) ? MonologLogger::NOTICE : MonologLogger::DEBUG;
        $logFile = $this->logFilePath($isCLI, $config);
        $rollbarConfig = $this->rollbarConfig($config);

        // Build a Monolog Logger
        $log = new MonologLogger($this->app->environment());
        $handler = new StreamHandler($logFile, $fileLogLevel);
        $handler->setFormatter(new LineFormatter(self::LOG_FORMAT, self::DATE_FORMAT));
        $log->pushHandler($handler);


        // Optionally, set up Rollbar
        if ($rollbarConfig) {
            $handler = new MonologHandler(new RollbarLogger($rollbarConfig), $rollbarConfig['level']);
            $handler->setApp($this->app);
            $log->pushHandler($handler);
        }

        return $log;
    }

    private function detectCommandLineInterface(): bool
    {
        return php_sapi_name() === 'cli';
    }

    private function logFilePath(bool $isCli, $config): string
    {
        $pathKey = ($isCli) ? 'path_cli' : 'path';

        return $config[$pathKey] ?? storage_path(self::LOG_FILE_FALLBACK);
    }

    private function rollbarConfig($config): ?array
    {
        if (empty($config['rollbar_token'])) {
            return null;
        }

        return [
            'driver' => 'monolog',
            'handler' => \Rollbar\Laravel\MonologHandler::class,
            'access_token' => $config['rollbar_token'],
            'level' => $config['rollbar_level'] ?? 'notice',
            'person_fn' => 'user_rollbar',
            'capture_email' => false,
            'capture_username' => true
        ];
    }
}
