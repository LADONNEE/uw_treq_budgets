<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer  $id
 * @property string   $tag_type
 * @property string   $workday_code
 * @property string   $name
 * @property integer  $hierarchy_id
 * @property integer  $cc_worktag_id
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * ----------   Relationships   ----------
 * @property BudgetBiennium[] $budgets
 * @property Worktag $costCenter
 */
class Worktag extends Model
{
    const TYPE_COST_CENTER = 'CostCenter';
    const TYPE_GIFT = 'Gift';
    const TYPE_GRANT = 'Grant';
    const TYPE_PROGRAM = 'Program';

    protected $table = 'worktags';
    protected $fillable = [
        'tag_type',
        'workday_code',
        'name',
        'hierarchy_id',
        'cc_worktag_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function hasCostCenter(): bool
    {
        return $this->cc_worktag_id && $this->costCenter;
    }

    public function budgets()
    {
        return $this->belongsToMany(BudgetBiennium::class, 'worktags_budgets', 'worktag_id', 'budget_id')
            ->where('budget_biennium_view.biennium', setting('current-biennium'))
            ->orderBy('budget_biennium_view.budgetno');
    }

    public function costCenter()
    {
        return $this->hasOne(Worktag::class, 'id', 'cc_worktag_id');
    }
}
