<?php

namespace App\AuthNotify;

/**
 * Send a notification REST request to the COLLEGE database Person Auth Log
 */
class NotifyCollegePerson
{
    public function handle(UserModified $event)
    {
        $notify = new CollegeAuthNotifyMultipleRoles(
            'budgets',
            config('services.auth_notify.url'),
            config('services.auth_notify.token')
        );
        $notify->notifyQuietly($event->userUwnetid, null, $event->actorUwnetid);
    }
}
