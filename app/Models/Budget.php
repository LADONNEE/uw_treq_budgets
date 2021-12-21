<?php

namespace App\Models;

use Carbon\Carbon;

/**
* @property integer $id
* @property string $budgetno
* @property boolean $is_coe
* @property integer $pi_person_id
* @property integer $fiscal_person_id
* @property integer $reconciler_person_id
* @property integer $business_person_id
* @property string $non_coe_name
* @property string $purpose_brief
* @property string $food
* @property Carbon $created_at
* @property Carbon $updated_at
* ---------   Relationships   ---------
* @property Person $business
* @property Note $foodNote
* @property Person $manager
* @property Note[] $notes
* @property BudgetPerson[] $people
* @property Note $purpose
* @property Person $reconciler
* @property UwBudget $uw
*/
class Budget extends AbstractModel
{
    protected $table = 'budgets';
    protected $fillable = [
        'budgetno',
        'is_coe',
        'pi_person_id',
        'fiscal_person_id',
        'reconciler_person_id',
        'business_person_id',
        'non_coe_name',
        'purpose_brief',
        'food',
    ];
    protected $dates = [
        'EffectiveDate',
        'TotalPeriodBeginDate',
        'TotalPeriodEndDate',
        'created_at',
        'updated_at',
    ];

    public static $foodOptions = [
        'N' => 'Food Not Allowed',
        'Y' => 'Food Allowed',
        'F' => 'Food Allowed, Food Form Required',
    ];

    public function getFoodPolicy()
    {
        if ($this->food && isset(self::$foodOptions[$this->food])) {
            return self::$foodOptions[$this->food];
        }
        return $this->food;
    }

    public function getNameAttribute($value)
    {
        return $this->uw->BudgetName;
    }

    public function setPiPersonIdAttribute($value)
    {
        $this->attributes['pi_person_id'] = $value;
        if (!$this->business_person_id) {
            $this->attributes['business_person_id'] = $value;
        }
    }

    public function business()
    {
        return $this->hasOne(Person::class, 'person_id', 'business_person_id');
    }

    public function foodNote()
    {
        return $this->hasOne(Note::class, 'budget_id', 'id')
            ->where('section', 'food')
            ->orderBy('created_at')
            ->withDefault([
                'budget_id' => $this->id,
                'section' => 'food',
            ]);
    }

    public function manager()
    {
        return $this->hasOne(Person::class, 'person_id', 'fiscal_person_id');
    }

    public function notes()
    {
        return $this->hasMany(Note::class, 'budget_id', 'id')
            ->where('section', 'budget')
            ->orderBy('created_at', 'desc');
    }

    public function people()
    {
        return $this->hasMany(BudgetPerson::class, 'budget_id', 'id')->with('person');
    }

    public function purpose()
    {
        return $this->hasOne(Note::class, 'budget_id', 'id')
            ->where('section', 'purpose')
            ->orderBy('created_at')
            ->withDefault([
                'budget_id' => $this->id,
                'section' => 'purpose',
            ]);
    }

    public function reconciler()
    {
        return $this->hasOne(Person::class, 'person_id', 'reconciler_person_id');
    }

    public function uw()
    {
        return $this->belongsTo(UwBudget::class, 'budgetno', 'budgetno')
            ->where('uw_budgets_cache.BienniumYear', setting('current-biennium'));
    }
}
