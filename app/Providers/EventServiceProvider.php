<?php

namespace App\Providers;

use App\AuthNotify\NotifyCollegePerson;
use App\AuthNotify\UserModified;
use App\Events\EffortReportUpdated;
use App\Listeners\UpdateEffortReportWorkflow;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserModified::class => [
            NotifyCollegePerson::class,
        ],
        EffortReportUpdated::class => [
            UpdateEffortReportWorkflow::class,
        ],
    ];

    public function boot()
    {
        parent::boot();

        //
    }
}
