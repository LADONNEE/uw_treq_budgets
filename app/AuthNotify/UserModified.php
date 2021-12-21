<?php

namespace App\AuthNotify;

class UserModified
{
    public $userUwnetid;
    public $actorUwnetid;

    public function __construct($userUwnetid, $actorUwnetid)
    {
        $this->userUwnetid = $userUwnetid;
        $this->actorUwnetid = $actorUwnetid;
    }
}
