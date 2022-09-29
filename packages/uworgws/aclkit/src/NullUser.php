<?php
namespace Uworgws\Aclkit;

use Uworgws\Aclkit\Contracts\UserWithRoles;

class NullUser implements UserWithRoles
{
    public function getRoles()
    {
        return [];
    }
}
