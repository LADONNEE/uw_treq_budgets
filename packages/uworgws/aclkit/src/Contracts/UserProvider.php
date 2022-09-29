<?php
namespace Uworgws\Aclkit\Contracts;

interface UserProvider
{
    /**
     * Service that returns UserWithRoles
     * Input $user might be a user object, user identifier (login name or id), or null in
     * which case provider is expected to locate currently logged in user
     * @param mixed $user
     * @return UserWithRoles
     */
    public function getUser($user);
}
