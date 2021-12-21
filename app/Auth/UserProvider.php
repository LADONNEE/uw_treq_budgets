<?php
namespace App\Auth;

use Uwcoenvws\Aclkit\Contracts\UserWithRoles;

class UserProvider implements \Uwcoenvws\Aclkit\Contracts\UserProvider
{
    protected $current;

    /**
     * Service that returns UserWithRoles
     * Input $user might be a user object, user identifier (login name or id), or null in
     * which case provider is expected to locate currently logged in user
     * @param mixed $user
     * @return UserWithRoles
     */
    public function getUser($user = null)
    {
        if ($user === null) {
            return $this->currentUser();
        }
        if ($user instanceof User) {
            return $user;
        }
        return new User((string) $user);
    }

    /**
     * Currently logged in user
     * This implementation retrieves UW NetID from Apache mod Shibboleth and creates user
     * from that identity.
     * @return User
     */
    public function currentUser()
    {
        if (!$this->current instanceof User) {
            $locator = new UwIdentityLocator;
            $uwnetid = $locator->getIdentity();
            if ($uwnetid) {
                $this->current = new User($uwnetid);
            } else {
                $this->current = new UserAnonymous();
            }
        }
        return $this->current;
    }
}
