<?php

namespace App\Models;

/**
 * @property integer $uw_budget_id
 * @property integer $budget_id
 * @property integer $id // alias of $budget_id
 * @property string $budgetno
 * @property string $biennium
 * @property string $name
 * @property integer $BudgetStatus
 * @property integer $fiscal_person_id
 * @property integer $reconciler_person_id
 * @property integer $business_person_id
 * @property string $purpose_brief
 * @property string $food
 * ---------   Relationships   ---------
 * @property Person $business
 * @property Person $manager
 * @property Note $purpose
 * @property Person $reconciler
 * @property UwBudget $uw
 */
class BudgetBiennium extends AbstractModel
{
    protected $table = 'budget_biennium_view';
    protected $fillable = [];
    protected $casts = [];

    public function getFoodPolicy()
    {
        if ($this->food && isset(Budget::$foodOptions[$this->food])) {
            return Budget::$foodOptions[$this->food];
        }
        return $this->food;
    }

    public function getStatusDescription()
    {
        return BudgetStatusNames::full($this->BudgetStatus);
    }

    public function getStatusShort()
    {
        return BudgetStatusNames::short($this->BudgetStatus);
    }

    public function business()
    {
        return $this->hasOne(Person::class, 'person_id', 'business_person_id');
    }

    public function manager()
    {
        return $this->hasOne(Person::class, 'person_id', 'fiscal_person_id');
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

    public function delete(array $options = [])
    {
        //
    }

    public function save(array $options = [])
    {
        //
    }

    public function uw()
    {
        return $this->belongsTo(UwBudget::class, 'uw_budget_id', 'id');
    }
}
