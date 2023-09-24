<?php
/**
 * @package edu.uw.environment.college
 */

/**
 * MergeHistory record, storable in database.
 * Laravel Eloquent ORM
 */

namespace App\Models;

/**
 * @property integer $id
 * @property integer $keep_person_id
 * @property integer $merge_person_id
 * @property string $merged_data
 * @property integer $merged_by_person_id
 * @property boolean $complete
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class MergeHistory extends AbstractModel
{
    protected $table = 'p_merge_history';
    protected $fillable = [
        'keep_person_id',
        'merge_person_id',
        'merged_data',
        'merged_by_person_id',
        'complete',
    ];
    protected $casts = [
        'created_at',
        'updated_at',
    ];


}
