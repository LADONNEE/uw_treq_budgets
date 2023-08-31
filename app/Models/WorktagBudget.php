<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer  $id
 * @property integer  $budget_id
 * @property integer  $worktag_id
 * @property string   $edited_by
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * ----------   Relationships   ----------
 */
class WorktagBudget extends Model
{
    protected $table = 'worktags_budgets';
    protected $fillable = [
        'budget_id',
        'worktag_id',
        'edited_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
