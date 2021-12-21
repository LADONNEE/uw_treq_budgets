<?php
/**
 * @package edu.uw.education.educ
 */

/**
 * User object that has zero or many application roles
 * @author hanisko
 */

namespace App\Auth;

/**
 * @property integer $person_id
 * @property string $uwnetid
 * @property string $firstname
 * @property string $lastname
 */
interface UserWithRolesInterface
{
    /**
     * Return the list of roles this user has
     * @return array
     */
    public function getRoles();

    /**
     * Return true if the user has the specified role
     * @param $role
     * @return boolean
     */
    public function hasRole($role);
}
