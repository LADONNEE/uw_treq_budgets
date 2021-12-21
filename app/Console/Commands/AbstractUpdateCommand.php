<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

/**
 * Wrapper to expose an UpdateJob class as an artisan command
 */
class AbstractUpdateCommand extends Command
{
    /** @var string  artisan command string */
    protected $signature = 'update:SOMETHING';

    /** @var string  artisan process description */
    protected $description = 'Update SOMETHING data';

    /** @var string  job class to be executed */
    protected $jobClass = 'App\My\Import\Job';

    private $_updateLog;

    public function handle()
    {
        $this->getLogger();
        $job = app()->make($this->jobClass);
        $job->run();
    }

    public function getLogger(): \Psr\Log\LoggerInterface
    {
        if (!$this->_updateLog) {
            $this->startUpdateLogging();
        }
        return $this->_updateLog;
    }

    private function startUpdateLogging()
    {
        $logChannels = ['updates'];

        if ($this->option('quiet')) {
            if ($this->shouldReportToRollbar()) {
                $logChannels[] = 'rollbar';
                config()->set('logging.channels.rollbar.level', 'error');
            }
        } else {
            $logChannels[] = 'stdout';
        }

        $this->_updateLog = logger()->stack($logChannels);

        app()->instance('log', $this->_updateLog);
        app()->instance('Psr\Log\LoggerInterface', $this->_updateLog);
    }

    /**
     * True is project + environment logging is set to report errors to Rollbar
     * @return bool
     */
    private function shouldReportToRollbar(): bool
    {
        if (config('logging.default') !== 'notify') {
            return false;
        }

        $channels = config('logging.channels');

        return isset($channels['rollbar'])
            && isset($channels['rollbar']['access_token'])
            && $channels['rollbar']['access_token'];
    }
}
