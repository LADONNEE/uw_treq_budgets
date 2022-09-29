<?php
namespace AppTest;

use Uworgws\Aclkit\Contracts\UserProvider;
use Uworgws\Aclkit\Contracts\UserWithRoles;

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