<?php

namespace App\Console\Commands;

use App\Logging\AddStdOut;
use Illuminate\Console\Command;
use Psr\Log\LoggerInterface;

class CommandWithLogging extends Command
{
    protected $signature = 'command:trigger';
    protected $description = 'What this command does';

    private $_stdoutStarted = false;

    /**
     * Get a log instance that can be passed to update jobs
     *
     * If artisan command was run without the --quiet option, this will also add standard output
     * as a channel where log messages are sent.
     */
    protected function getLog(): LoggerInterface
    {
        $this->logToStdOut();
        return app(LoggerInterface::class);
    }

    protected function logToStdOut()
    {
        if (!$this->_stdoutStarted && !$this->option('quiet')) {
            AddStdOut::toStack();
            $this->_stdoutStarted = true;
        }
    }
}
