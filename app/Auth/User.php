<?php
namespace App\Auth;

use App\Contracts\HasNames;
use App\Models\Contact;
use App\Models\Person;
use App\Models\Role;
use Uworgws\Aclkit\Contracts\UserWithRoles;

class User implements HasNames, UserWithRoles
{
    public $uwnetid;
    public $person_id;
    public $firstname;
    public $lastname;

    protected $roles;

    public function __construct($uwnetid, $initData = null)
    {
        $this->uwnetid = $uwnetid;
        if ($initData) {
            $this->init($initData);
        } else {
            $this->load();
        }
    }

    /**
     * Runtime append for bulk loading Users with roles
     * This does not persist the user role
     * @param string $role
     */
    public function addRole($role)
    {
        $this->roles[] = $role;
    }

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
        return $this->uwnetid;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function hasRole($role)
    {
        return in_array($role, $this->getRoles());
    }

    public function init($data)
    {
        if (is_array($data)) {
            $this->person_id = $data['person_id'] ?? null;
            $this->firstname = $data['firstname'] ?? null;
            $this->lastname = $data['lastname'] ?? null;
            $this->roles = $data['roles'] ?? [];
        } else {
            $this->person_id = $data->person_id ?? null;
            $this->firstname = $data->firstname ?? null;
            $this->lastname = $data->lastname ?? null;
            $this->roles = $data->roles ?? [];
        }
    }

    protected function load()
    {
        $person = Person::where('uwnetid', $this->uwnetid)->first();
        if ($person) {
            $this->person_id = $person->person_id;
            $this->firstname = $person->firstname;
            $this->lastname = $person->lastname;
        }
        $this->roles = $this->loadRoles();
    }

    public function getFirstLast()
    {
        $out = "{$this->firstname}{$this->lastname}";
        return ($out) ?: $this->getIdentifier();
    }

    public function getLastFirst()
    {
        if ($this->firstname && $this->lastname) {
            return "{$this->lastname}, {$this->firstname}";
        }
        $out = "{$this->lastname}{$this->firstname}";
        return ($out) ?: $this->getIdentifier();
    }

    public function getLName()
    {
        if ($this->lastname) {
            return $this->lastname;
        }
        return $this->getIdentifier();
    }

    private function loadRoles()
    {
        $roles = Role::where('uwnetid', $this->uwnetid)
            ->pluck('role')
            ->all();
        $contact = Contact::firstWhere('uwnetid', $this->getIdentifier());

        if (isset($contact)) {
            $roles[] = 'budget:viewer';
        }

        return $roles;
    }
}
