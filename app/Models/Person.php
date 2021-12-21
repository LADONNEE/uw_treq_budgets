<?php

namespace App\Models;

use App\Auth\User;
use App\Contracts\HasNames;

/**
 * @property integer $person_id
 * @property string $uwnetid
 * @property integer $studentno
 * @property integer $employeeid
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 */
class Person extends ReadOnlyModel implements HasNames
{
    protected $table = 'shared.uw_persons';
    protected $primaryKey = 'person_id';

    public function getFirst()
    {
        return $this->firstname;
    }

    public function getLast()
    {
        return $this->lastname;
    }

    public function getIdentifier()
    {
        if ($this->uwnetid) {
            return $this->uwnetid;
        }
        return "person_id:$this->person_id";
    }

    public function getIdAttribute($value)
    {
        return $this->attributes['person_id'] ?? null;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if (!$this->uwnetid) {
            return null;
        }
        if ($this->user === null) {
            $this->user = new User($this->uwnetid);
        }
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }
}
