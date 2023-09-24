<?php

namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer $uw_code
 * @property string $short
 * @property string $full
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class StatusLookup extends AbstractModel
{
    protected $table = 'status_lookup';
    protected $primaryKey = 'uw_code';
    public $incrementing = false;
    protected $fillable = [
        'uw_code',
        'short',
        'full',
    ];
    protected $casts = [
        'created_at',
        'updated_at',
    ];
}
