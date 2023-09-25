<?php
namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer  $id
 * @property string   $name
 * @property string   $value
 * @property integer  $changed_by
 * @property Carbon   $created_at
 * @property Carbon   $updated_at
 */
class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'name',
        'value',
        'changed_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

}
