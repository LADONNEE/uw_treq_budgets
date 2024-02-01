<?php
/**
 * @package app.treq.school
 */

/**
 * User role
 * Uses Laravel Eloquent ORM
 */

namespace App\Models;

use Carbon\Carbon;

/**
 * @property integer $id
 * @property string $uwnetid
 * @property string $role
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class Role extends AbstractModel
{
    protected $table = 'roles';
    protected $fillable = [
        'uwnetid',
        'role',
    ];

}
