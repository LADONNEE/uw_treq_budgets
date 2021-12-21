<?php

namespace App\Models;

use App\Auth\User;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * @property integer $id
 * @property integer $faculty_contact_id
 * @property string $budget_id
 * @property string $pca_code
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property float $allocation_percent
 * @property float $additional_pay_fixed_monthly
 * @property string $type
 * @property string $allocation_category
 * @property string $additional_pay_category
 * @property string $note
 * @property string $edited_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * ----------   Relationships   ----------
 * @property Budget $budget
 * @property BudgetBiennium $budgetBiennium
 * @property Contact $contact
 */
class Allocation extends Model
{
    protected $fillable = [
        'faculty_contact_id',
        'budget_id',
        'pca_code',
        'start_at',
        'end_at',
        'allocation_percent',
        'additional_pay_fixed_monthly',
        'type',
        'allocation_category',
        'additional_pay_category',
        'note',
        'edited_by',
    ];

    public static $typeOptions = [
        'ALLOCATION' => 'Allocation',
        'ADDITIONAL PAY' => 'Additional Pay',
    ];

    public static $allocationCategories = [
        'STATE LINE' => 'State Line',
        'GRANT/RESEARCH' => 'Grant/Research',
        'SELF SUSTAINING' => 'Self Sustaining',
        'CROSS UNIT EFFORT' => 'Cross Unit Effort',
    ];

    public static $additionalPayCategories = [
        'ADS' => 'ADS - Administrative Supplement',
        'ENS' => 'ENS - Endowed Supplement',
        'TPS' => 'TPS - Temporary Pay Supplement',
    ];

    public function budget()
    {
        return $this->hasOne(Budget::class, 'id', 'budget_id');
    }

    public function budgetBiennium()
    {
        return $this->hasOne(BudgetBiennium::class, 'budget_id', 'budget_id');
    }

    public function contact()
    {
        return $this->hasOne(Contact::class, 'id', 'faculty_contact_id');
    }

    public function editedBy(User $user)
    {
        $this->edited_by = $user->person_id;
    }
}
