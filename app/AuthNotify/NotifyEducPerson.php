<?php

namespace App\AuthNotify;

/**
 * Send a notification REST request to the EDUC database Person Auth Log
 */
class NotifyEducPerson
{
    public function handle(UserModified $event)
    {
        $notify = new EducAuthNotifyMultipleRoles(
            'budgets',
            config('services.auth_notify.url'),
            config('services.auth_notify.token')
        );
        $notify->notifyQuietly($event->userUwnetid, null, $event->actorUwnetid);
    }
}
