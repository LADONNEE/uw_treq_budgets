<?php
namespace App\Auth;

class UserAnonymous extends User
{
    public function __construct()
    {
        parent::__construct(null);
    }

    protected function load()
    {
        $this->person_id = 0;
        $this->firstname = null;
        $this->lastname = 'Not Logged In';
        $this->roles = [];
    }
}
