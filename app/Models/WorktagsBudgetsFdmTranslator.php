<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer  $id
 * @property string   $tag_type
 * @property string   $workday_code
 * @property string   $budgetno
 * @property string   $system
 * @property string   $workday_name
 * @property string   $mapping_status
 * @property string   $orgcode
 * @property Carbon   $loaded_at
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * ----------   Relationships   ----------
 */
class WorktagsBudgetsFdmTranslator extends Model
{
    protected $table = 'worktags_budgets_fdm_translator';
    protected $fillable = [
        'tag_type',
        'workday_code',
        'budgetno',
        'system',
        'workday_name',
        'mapping_status',
        'orgcode',
        'loaded_at',
    ];
    protected $casts = [
        'loaded_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

}
