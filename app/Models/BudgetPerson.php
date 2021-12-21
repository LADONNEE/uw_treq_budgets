<?php

namespace App\Models;

use App\Contracts\HasNames;
use Carbon\Carbon;

/**
 * @property integer $id
 * @property integer $person_id
 * @property integer $budget_id
 * @property string $description
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class BudgetPerson extends AbstractModel implements HasNames
{
    use IsPersonTrait;

    public $firstname;
    public $lastname;
    protected $loaded = false;
    protected $table = 'budgets_people';
    protected $fillable = [
        'budget_id',
        'person_id',
        'description',
        'eventtype',
        'data',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function person()
    {
        return $this->belongsTo(Person::class, 'person_id', 'person_id');
    }

    public function getFirst()
    {
        $this->lazyLoad();
        return $this->firstname;
    }

    public function getLast()
    {
        $this->lazyLoad();
        return $this->lastname;
    }

    protected function lazyLoad()
    {
        if ($this->loaded) {
            return;
        }
        $person = Person::where('person_id', $this->person_id)->first();
        if ($person) {
            $this->person_id = $person->person_id;
            $this->firstname = $person->firstname;
            $this->lastname = $person->lastname;
        }
        $this->loaded = true;
    }

}
