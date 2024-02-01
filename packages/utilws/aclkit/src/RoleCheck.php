<?php
namespace Utilws\Aclkit;

use Utilws\Aclkit\Contracts\UserProvider;
use Utilws\Aclkit\Contracts\UserWithRoles;
use Utilws\Aclkit\Exceptions\RoleNotDefined;

class RoleCheck
{
    protected $userProvider;
    protected $satisfying;

    public function __construct(array $config, UserProvider $userProvider = null)
    {
        $this->userProvider = $userProvider;
        $this->parseConfig($config);
    }

    public function getSatisfyingRoleMap()
    {
        return $this->satisfying;
    }

    public function hasRole($role, $user = null)
    {
        $user = $this->toUser($user);
        return $this->roleSatisfied($role, $user->getRoles());
    }

    public function hasRoleDebug($role, $user = null)
    {
        $user = $this->toUser($user);
        return [
            'requiredRole' => $role,
            'validRole' => isset($this->satisfying[$role]) ? 'YES' : 'NO',
            'satisfyingRoles' => isset($this->satisfying[$role]) ? $this->satisfying[$role] : [],
            'userRoles' => $user->getRoles(),
            'hasRole' => $this->roleSatisfied($role, $user->getRoles()),
        ];
    }

    public function roleSatisfied($requiredRole, array $hasRoles)
    {
        if (!isset($this->satisfying[$requiredRole])) {
            throw new RoleNotDefined($requiredRole);
        }
        foreach ($this->satisfying[$requiredRole] as $satisfies) {
            if (in_array($satisfies, $hasRoles)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Read role configuration array into operational structure
     * @param $config
     */
    protected function parseConfig(array $config)
    {
        $this->satisfying = [];
        foreach ($config as $role => $inherits) {
            $this->satisfying[$role] = $this->getSatisfyingRoles($role, $config);
        }
    }

    /**
     * Extracts all satisfying roles for a specific role from a role inheritance map
     * @param string $role
     * @param array $fullmap
     * @param mixed $loopDetect
     * @return array
     */
    protected function getSatisfyingRoles($role, $fullmap, $loopDetect = null)
    {
        $out = [ $role ];
        if ($loopDetect === null) {
            $loopDetect = [ $role ];
        }
        // find all roles in the configuration that directly inherit the base role
        foreach ($fullmap as $otherRole => $inheritedRoles) {
            if (in_array($role, $inheritedRoles)) {
                $out[] = $otherRole;
                // recursive search for $otherRole
                if (in_array($otherRole, $loopDetect)) {
                    continue;
                }
                $loopDetect[] = $otherRole;
                $moreroles = $this->getSatisfyingRoles($otherRole, $fullmap, $loopDetect);
                $out = array_merge($out, $moreroles);
            }
        }
        return array_unique($out);
    }

    /**
     * @param mixed $user
     * @return UserWithRoles
     */
    protected function toUser($user)
    {
        if ($user instanceof UserWithRoles) {
            return $user;
        }
        if ($this->userProvider instanceof UserProvider) {
            return $this->userProvider->getUser($user);
        }
        return new NullUser();
    }

}
