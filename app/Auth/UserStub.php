<?php
namespace App\Auth;

class UserStub extends User
{
    const STUB_PERSON_ID = 99999999;

    public function __construct()
    {
        parent::__construct('testing');
        $this->setup(func_get_args());
    }

    protected function lazyLoad()
    {
        if ($this->loaded) {
            return;
        }
        $this->person_id = self::STUB_PERSON_ID;
        $this->firstname = 'User-stub-first';
        $this->lastname = 'User-stub-last';
        $this->roles = [];
        $this->loaded = true;
    }

    private function setup(array $args)
    {
        $this->lazyLoad();
        foreach ($args as $arg) {
            if (is_string($arg) && $arg !== '') {
                $this->uwnetid = $arg;
            } elseif (is_array($arg)) {
                $this->roles = $arg;
            }
        }
    }
}
