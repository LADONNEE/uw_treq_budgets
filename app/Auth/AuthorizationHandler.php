<?php
/**
 * @package edu.uw.org.college
 */

/**
 * Adds and removes user authorizations within the COLLEGE project
 */

namespace App\Auth;

use App\Models\Contact;
use App\Models\Person;
use App\Models\Role;

class AuthorizationHandler
{

    /**
     * Give $roles to $person
     * Adds system authorizations and logs changes
     * @param Person $person
     * @param mixed $roles
     */
    public function addRoles(Person $person, $roles)
    {
        if (empty($person->uwnetid)) {
            return;
        }
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        foreach ($roles as $role) {
            $this->addRoleItem($person->uwnetid, $role);
        }
    }

    /**
     * Change $person roles by adding and removing roles in one loggable event
     * Changes system authorizations and logs changes
     * @param Contact $contact
     * @param mixed $addRoles
     * @param mixed $removeRoles
     */
    public function changeRoles(Contact $contact, $addRoles, $removeRoles)
    {
        if (empty($contact->uwnetid)) {
            return;
        }
        if (!is_array($addRoles)) {
            $addRoles = [$addRoles];
        }
        foreach ($addRoles as $role) {
            $this->addRoleItem($contact->uwnetid, $role);
        }
        if (!is_array($removeRoles)) {
            $removeRoles = [$removeRoles];
        }
        foreach ($removeRoles as $role) {
            $this->removeRoleItem($contact->uwnetid, $role);
        }
    }

    protected function addRoleItem($uwnetid, $addRole)
    {
        $exists = Role::where('uwnetid', $uwnetid)->where('role', $addRole)->first();
        if (!$exists) {
            $role = new Role;
            $role->uwnetid = $uwnetid;
            $role->role = $addRole;
            $role->save();
        }
    }

    protected function removeRoleItem($uwnetid, $removeRole)
    {
        $exists = Role::where('uwnetid', $uwnetid)->where('role', $removeRole)->first();
        if ($exists) {
            $exists->delete();
        }
    }

}
