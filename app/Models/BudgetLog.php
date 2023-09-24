<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer $id
 * @property \Carbon\Carbon $created_at
 * @property string $uwnetid
 * @property string $eventtype
 * @property integer $person_id
 * @property integer $budget_id
 * @property string $data
 */
class BudgetLog extends AbstractModel
{
    protected $table = 'budgetlog';
    protected $casts = [
        'changed_at',
    ];
    protected $fillable = [
        'uwnetid',
        'eventtype',
        'budget_id',
        'person_id',
        'data',
    ];
    public $timestamps = false;
    protected $_data;

    public function getData($name)
    {
        if ($this->_data === null) {
            if ($this->data) {
                $this->_data = json_decode($this->data, true);
            } else {
                $this->_data = [];
            }
        }
        return (isset($this->_data[$name])) ? $this->_data[$name] : null;
    }

    public function writeFoodPolicyLocal($user, Budget $budget)
    {
        $this->created_at = Carbon::now();
        $this->uwnetid = $user;
        $this->eventtype = 'food-policy';
        $this->budget_id = $budget->id;
        $this->data = json_encode([
            'food_local' => $budget->food_local,
            'description' => $budget->food_local . ' ' . $budget->getFoodLocalDescription(),
            'budgetno' => $budget->budgetno,
        ]);
        $this->save();
    }

    public function writeAssignFiscal($user, $person, Budget $budget)
    {
        if (!$person instanceof Person) {
            return $this->writeRemoveFiscal($user, $budget);
        }
        $this->created_at = Carbon::now();
        $this->uwnetid = $user;
        $this->eventtype = 'assign-fiscal';
        $this->person_id = $person->person_id;
        $this->budget_id = $budget->id;
        $this->data = json_encode([
            'Name' => eFirstLast($person),
            'budgetno' => $budget->budgetno,
        ]);
        $this->save();
    }

    public function writeRemoveFiscal($user, Budget $budget)
    {
        $this->created_at = Carbon::now();
        $this->uwnetid = $user;
        $this->eventtype = 'assign-fiscal';
        $this->person_id = null;
        $this->budget_id = $budget->id;
        $this->data = json_encode([
            'Name' => '(removed fiscal contact)',
            'budgetno' => $budget->budgetno,
        ]);
        $this->save();
    }

    public function getDescription()
    {
        switch ($this->eventtype) {
            case 'add-fiscal':
                $message = 'added ' . $this->getData('Name') . ' to fiscal team';
                break;
            case 'remove-fiscal':
                $message = 'removed ' . $this->getData('Name') . ' from fiscal team';
                break;
            case 'assign-fiscal':
                $message = 'assigned ' . $this->getData('Name') . ' as fiscal contact for ' . $this->getData('budgetno');
                break;
            case 'food-policy':
                $message = 'set COENV food policy to "' . $this->getData('description') . '" for ' . $this->getData('budgetno');
                break;
            default:
                $message = $this->eventtype;
                break;
        }
        return $message;
    }

}
