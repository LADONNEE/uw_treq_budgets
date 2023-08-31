<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer  $id
 * @property string   $tag_type
 * @property string   $workday_code
 * @property string   $name
 * @property integer  $parent_id
 * @property string   $parent_workday_code
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 *
 * ----------   Relationships   ----------
 * @property Worktag[] $worktags
 * @property WorktagHierarchy[] $children
 */
class WorktagHierarchy extends Model
{
    protected $table = 'worktag_hierarchy';
    protected $fillable = [
        'tag_type',
        'workday_code',
        'name',
        'parent_id',
        'parent_workday_code',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->orderBy('workday_code');
    }

    public function worktags()
    {
        return $this->hasMany(Worktag::class, 'hierarchy_id', 'id')->orderBy('workday_code');
    }
}
