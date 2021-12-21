<?php

namespace App\Forms\Budget;

use App\Forms\UserRolesForm;

class UserForm extends UserRolesForm
{

    /**
     * Roles and add-on permissions managed by this form
     * Assoc array with 'role:value' => 'Friendly description'
     * @var array
     */
    protected $roles = [
        '' => 'No Authorization',
        'budget:viewer' => 'Viewer',
        'budget:fiscal' => 'Fiscal',
        'budget:admin' => 'Admin',
    ];

    protected $addOns = [
        'reconciler' => 'Reconciler',
        'manager' => 'Manager',
        'manage-faculty' => 'Manage Faculty',
        'act-on-behalf' => 'Act on Behalf',
        'workday' => 'Workday',
    ];

    /**
     * Used for Person logging, describes source of Person data changes
     * Examples 'appreview_auth', 'student_auth'
     * @var string
     */
    protected $personUpdateContext = 'context_auth';

}
