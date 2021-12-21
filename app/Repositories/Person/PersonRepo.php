<?php
/**
 * @package edu.uw.education.educ
 */
/**
 * Repository for Person Entities
 */
namespace App\Repositories\Person;

use App\Auth\User;
use App\Models\MergeHistory;
use App\Models\Person;
use App\Models\Role;
use App\Repositories as Repo;

class PersonRepo
{
    protected $identifiers = [
        'uwnetid',
        'systemkey',
        'studentno',
        'employeeid',
        'regid',
        'wacertificateno',
    ];
    protected $soarsToId = [];
    protected $uwnetidToId = [];
    protected $resolvedCache = [];

    /**
     * List of person that have a system authorization (record on roles table)
     * Optionally limited to roles for an application context
     * @param string|null $context
     * @return Person[]
     */
    public function authorized($context = null)
    {
        // Build role arrays
        $results = Role::orderBy('uwnetid')->get();
        $roles = [];
        foreach ($results as $role) {
            if (!isset($roles[$role->uwnetid])) {
                $roles[$role->uwnetid] = [];
            }
            $roles[$role->uwnetid][] = $role->role;
        }
        $query = Person::select(\DB::raw('DISTINCT shared.uw_persons.*'))
            ->join('roles', 'shared.uw_persons.uwnetid', '=', 'roles.uwnetid')
            ->orderBy('shared.uw_persons.lastname')
            ->orderBy('shared.uw_persons.firstname');
        if ($context) {
            $query->where('roles.role', 'like', $context . '%');
            $query->orWhere('roles.role', '=', 'super');
        }
        $persons = $query->get();
        foreach ($persons as $person) {
            $uRoles = (isset($roles[$person->uwnetid])) ? $roles[$person->uwnetid] : [] ;
            $person->setUser(new User($person->uwnetid, $person, $uRoles));
        }
        return $persons;
    }

    /**
     * Creates or updates Person record using $data
     * Handles name change, stores new name and creates Aka record
     * @param array|\stdClass $data
     */
    public function createOrUpdate($data)
    {
        if ($data instanceof \stdClass) {
            $data = json_decode(json_encode($data),true);
        }
        $query = Person::orderBy('id');
        foreach ($this->identifiers as $index) {
            if (!empty($data[$index])) {
                $query->orWhere($index, $data[$index]);
            }
        }
        $found = $query->get();
        if (count($found) == 0) {
            $person = new Person();
            $person->fill($data);
            $person->save();
            return;
        }
        $lastname = (isset($data['lastname'])) ? $data['lastname'] : null;
        $firstname = (isset($data['firstname'])) ? $data['firstname'] : null;
        unset($data['lastname']);
        unset($data['firstname']);
        foreach ($found as $person) {
            $person->fill($data);
            $person->changeName($lastname, $firstname);
            $person->save();
        }
    }

    public function fiscalIndex()
    {
        $people = $this->withRole('budget:fiscal');
        $out = [];
        foreach ($people as $person) {
            $out[$person->person_id] = eFirstLast($person);
        }
        return $out;
    }

    /**
     * Get an Person using the serial id
     * Returns null if not found
     * @param $id
     * @return Person|null
     */
    public function get($id)
    {
        return Person::find($id);
    }

    /**
     * Get a Person by UW NetID
     * @param string $uwnetid
     * @return Person|null
     */
    public function getByUwnetid($uwnetid)
    {
        return Person::where('uwnetid', $uwnetid)->first();
    }

    /**
     * Get a Person by person_id
     * Keeps reference to that Person for subsequent lookups
     * @param $person_id
     * @return Person
     */
    public function getCache($person_id)
    {
        if (isset($this->resolvedCache[$person_id])) {
            return $this->resolvedCache[$person_id];
        }
        $person = $this->get($person_id);
        if (!$person) {
            $person = new Person();
            $person->lastname = "Unknown ({$person_id})";
        }
        $this->resolvedCache[$person_id] = $person;
        return $this->resolvedCache[$person_id];
    }

    /**
     * Returns a Person id that matches provided personid from the old SOARS database
     * @param integer $soars_personid
     * @param null $not_found_value
     * @return integer|null
     */
    public function getPersonIdBySoarsId($soars_personid, $not_found_value = null)
    {
        if (isset($this->soarsToId[$soars_personid])) {
            return $this->soarsToId[$soars_personid];
        }
        $person = Person::where('import_id', $soars_personid)->first();
        if ($person) {
            $this->soarsToId[$soars_personid] = $person->person_id;
            return $person->person_id;
        }
        return $not_found_value;
    }

    /**
     * Returns a Person id that matches provided UW NetID
     * @param $uwnetid
     * @param null $not_found_value
     * @return integer|null
     */
    public function getPersonIdByUwnetid($uwnetid, $not_found_value = null)
    {
        if (isset($this->uwnetidToId[$uwnetid])) {
            return $this->uwnetidToId[$uwnetid];
        }
        $person = $this->getByUwnetid($uwnetid);
        if ($person) {
            $this->uwnetidToId[$uwnetid] = $person->person_id;
            return $person->person_id;
        }
        return $not_found_value;
    }

    /**
     * Accepts a $data array with indices matching Person fields and returns Person object
     * Attempts to match existing person records using any of the defined unique identifiers
     * on the person table. If no existing record can be matched, new Person is created and stored.
     * @param array|\stdClass $data
     * @return Person
     */
    public function importPersonData($data)
    {
        if ($data instanceof \stdClass) {
            $data = json_decode(json_encode($data),true);
        }
        $person = null;
        foreach ($this->identifiers as $index) {
            if (isset($data[$index])) {
                $person = Person::where($index, $data[$index])->first();
            }
            if ($person) {
                break;
            }
        }
        if (!$person) {
            $person = new Person();
        }
        $person->fill($data);
        $person->save();
        return $person;
    }

    public function missingName()
    {
        return Person::with('authorization')->whereNull('lastname')->orderBy('uwnetid')->get();
    }

    /**
     * Preloads identifier lookups
     */
    public function primeLookupCache()
    {
        $this->soarsToId = Person::whereNotNull('import_id')->pluck('id', 'import_id')->all();
        $this->uwnetidToId = Person::whereNotNull('uwnetid')->pluck('id', 'uwnetid')->all();
    }

    public function withRole($role)
    {
        return Person::select('shared.uw_persons.*')
            ->join('roles', function($join) {
                $join->on('shared.uw_persons.uwnetid', '=', 'roles.uwnetid');
            })
            ->where('roles.role', $role)
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->get();
    }

    public function withNoAppointment($paginate = 40)
    {
        return Person::select('shared.uw_persons.*')
            ->leftJoin('edw_appointment_cache', function($join) {
                $join->on('shared.uw_persons.person_id', '=', 'edw_appointment_cache.person_id');
            })
            ->whereNull('edw_appointment_cache.id')
            ->where('uwperson', 1)
            ->with('authorization')
            ->orderBy('lastname')
            ->orderBy('firstname')
            ->paginate($paginate);
    }

}
