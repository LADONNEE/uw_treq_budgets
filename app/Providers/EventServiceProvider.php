<?php

namespace App\Providers;

use App\AuthNotify\NotifyEducPerson;
use App\AuthNotify\UserModified;
use App\Events\EffortReportUpdated;
use App\Listeners\UpdateEffortReportWorkflow;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        UserModified::class => [
            NotifyEducPerson::class,
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
