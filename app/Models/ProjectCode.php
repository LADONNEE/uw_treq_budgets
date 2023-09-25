<?php

namespace App\Models;


/**
 * @property integer $id
 * @property string $code
 * @property string $description
 * @property string $allocation_type_frequency
 * @property string $purpose
 * @property string $pre_approval_required
 * @property string $action_item
 * @property string $workday_code
 * @property string $workday_description
 * @property integer $authorizer_person_id
 * @property integer $fiscal_person_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 */
class ProjectCode extends AbstractModel
{
    protected $table = 'project_codes';
    protected $primaryKey = 'id';

    protected $fillable = [
        'code',
        'description',
        'allocation_type_frequency',
        'purpose',
        'pre_approval_required',
        'action_item',
        'workday_code',
        'workday_description',
        'authorizer_person_id',
        'fiscal_person_id',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

}
