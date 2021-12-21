<?php

namespace App\Services;

use App\Contracts\HasNames;
use App\Models\Person;

class PersonLookup
{
    const UWNETID_REGEX = '/^[a-z0-9]{1,8}$/';
    protected $cacheIds = [];
    protected $cacheUwnetids = [];

    /**
     * @param mixed $value
     * @return HasNames
     */
    public function toPerson($value)
    {
        if ($value instanceof HasNames) {
            return $value;
        }
        if (is_numeric($value)) {
            $int = (int)$value;
            if ($int) {
                return $this->byId($int);
            }
        }
        if (preg_match(self::UWNETID_REGEX, $value)) {
            return $this->byUwnetid($value);
        }

        $value = (string)$value;
        return new Person([
            'lastname' => "(unknown person {$value})"
        ]);
    }

    /**
     * @param integer $person_id
     * @return Person
     */
    public function byId($person_id)
    {
        if (isset($this->cacheIds[$person_id])) {
            return $this->cacheIds[$person_id];
        }
        $found = Person::find($person_id);
        if ($found instanceof Person) {
            $this->cacheIds[$person_id] = $found;
        } else {
            $this->cacheIds[$person_id] = new Person([
                'lastname' => "(unknown person {$person_id})"
            ]);
        }
        return $this->cacheIds[$person_id];
    }

    /**
     * @param string $uwnetid
     * @return Person
     */
    public function byUwnetid($uwnetid)
    {
        if (isset($this->cacheUwnetids[$uwnetid])) {
            return $this->cacheUwnetids[$uwnetid];
        }
        $found = Person::where('uwnetid', $uwnetid)->first();
        if ($found instanceof Person) {
            $this->cacheUwnetids[$uwnetid] = $found;
        } else {
            $this->cacheUwnetids[$uwnetid] = new Person([
                'lastname' => "(unknown person {$uwnetid})",
                'uwnetid' => $uwnetid,
            ]);
        }
        return $this->cacheUwnetids[$uwnetid];
    }

}
