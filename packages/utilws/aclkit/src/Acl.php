<?php
namespace Utilws\Aclkit;

use Utilws\Aclkit\Contracts\UserProvider;

class Acl
{
    protected $paths;
    protected $roles;

    public function __construct(array $roleConfig, array $pathRules, UserProvider $userProvider, $basePaths = null)
    {
        $this->roles = new RoleCheck($roleConfig, $userProvider);
        $this->paths = new PathAuthorizer($pathRules, $basePaths);
    }

    /**
     * True if $user is allowed to access $path
     * @param string $path
     * @param mixed $user
     * @return boolean
     */
    public function allowedPath($path, $user = null)
    {
        $requiredRole = $this->paths->requiredRole($path);
        if ($requiredRole) {
            return $this->hasRole($requiredRole, $user);
        }
        return true;
    }

    /**
     * True if $user has this $role
     * @param string $role
     * @param mixed $user
     * @return boolean
     */
    public function hasRole($role, $user = null)
    {
        return $this->roles->hasRole($role, $user);
    }

}
