<?php
namespace Utilws\Aclkit;

use Utilws\Aclkit\Contracts\UserWithRoles;

class NullUser implements UserWithRoles
{
    public function getRoles()
    {
        return [];
    }
}
