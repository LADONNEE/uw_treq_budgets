<?php
namespace AppTest;

use Utilws\Aclkit\Contracts\UserWithRoles;

class UserStub implements UserWithRoles
{
    protected $roles;

    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    public function getRoles()
    {
        return $this->roles;
    }

}