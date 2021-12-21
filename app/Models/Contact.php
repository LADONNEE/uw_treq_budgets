<?php

namespace App\Models;

use App\Contracts\HasNames;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property integer $id
 * @property integer $person_id
 * @property string $uwnetid
 * @property integer $studentno
 * @property integer $employeeid
 * @property string $firstname
 * @property string $lastname
 * @property string $email
 * @property boolean $is_faculty
 * @property boolean $is_80_20
 * @property string $default_budget_id
 * @property integer $fiscal_person_id
 * @property Carbon $end_date
 *
 * ----------   Relationships   ----------
 * @property Budget $defaultBudget
 * @property EffortReport $latestEffortReport
 */
class Contact extends Model implements HasNames
{
    protected $fillable = [
        'person_id',
        'uwnetid',
        'studentno',
        'employeeid',
        'firstname',
        'lastname',
        'email',
        'is_faculty',
        'is_80_20',
        'default_budget_id',
        'fiscal_person_id',
        'end_at',
    ];

    public function getFirst()
    {
        return $this->firstname;
    }

    public function getLast()
    {
        return $this->lastname;
    }

    public function getFullName()
    {
        return Str::title($this->getFirst() . ' ' . $this->getLast());
    }

    public function getIdentifier()
    {
        if ($this->uwnetid) {
            return $this->uwnetid;
        }
        return "person_id:$this->person_id";
    }

    public function getFiscalPerson()
    {
        return Contact::where('id', $this->fiscal_person_id)->first();;
    }

    public function getFiscalPersonName()
    {
        return $this->getFiscalPerson()->getFullName();
    }

    public function defaultBudget()
    {
        return $this->belongsTo(Budget::class, 'default_budget_id', 'id');
    }

    public function latestEffortReport()
    {
        return $this->hasOne(EffortReport::class, 'faculty_contact_id', 'id')
            ->where('stage', '<>', EffortReport::STAGE_CANCELED)
            ->latest();
    }

    public static function personToContact($person_id)
    {
        $contact = Contact::where('person_id', $person_id)->first() ?? null;

        if (!$contact) {
            $person = Person::where('person_id', $person_id)->first();
            $contact = new Contact();
            $contact->person_id = $person->person_id;
            $contact->uwnetid = $person->uwnetid ?? null;
            $contact->firstname = $person->firstname ?? null;
            $contact->lastname = $person->lastname ?? null;
            $contact->email = $person->email ?? null;
            $contact->employeeid = $person->employeeid ?? null;
            $contact->studentno = $person->studentno ?? null;

            $contact->save();
        }

        return $contact->id;
    }

    public static function activeContacts(Collection $contacts)
    {
        $contactsNoEnd = $contacts->whereNull('end_at');
        return $contactsNoEnd->merge($contacts->where('end_at', '>=', Carbon::now()));
    }

    public static function inactiveContacts(Collection $contacts)
    {
        return $contacts->whereNotNull('end_at')->where('end_at', '<', Carbon::now());
    }

    public static function activeFacultyWithEffortReports()
    {
        $faculty = Contact::where('is_faculty', true)->orderBy('lastname')->get();
        return $faculty->filter(function ($value, $key) {
            if ($value->latestEffortReport) {
                if ($value->latestEffortReport->created_at > Carbon::now()->subDays(90)) {
                    return $value->latestEffortReport;
                }
            }
        });

    }
}
