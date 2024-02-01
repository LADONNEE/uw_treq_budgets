<?php
namespace AppTest;

use Utilws\Aclkit\Contracts\UserProvider;
use Utilws\Aclkit\Contracts\UserWithRoles;

class UserProviderStub implements UserProvider
{
    protected $stubUser;

    public function __construct(UserWithRoles $stubUser)
    {
        $this->stubUser = $stubUser;
    }

    public function getUser($user)
    {
        if ($user instanceof UserWithRoles) {
            return $user;
        }
        return $this->stubUser;
    }

}