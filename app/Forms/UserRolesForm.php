<?php

namespace App\Forms;

use App\Auth\AuthorizationHandler;
use App\Auth\User;
use App\AuthNotify\UserModified;
use App\Updaters\ContactUpdater;
use Illuminate\Support\Facades\DB;

/**
 * Assign a user application roles.
 * This is a general implementation for managing user roles. Each context can implement
 * this with a context specific list of roles to manage.
 */
class UserRolesForm extends Form
{
    /**
     * Roles managed by this form
     * Assoc array with 'role:value' => 'Friendly description'
     * @var array
     */
    protected $roles = [];
    protected $addOns = [];

    /**
     * Used for Person logging, describes source of Person data changes
     * Examples 'appreview_auth', 'student_auth'
     * @var string
     */
    protected $personUpdateContext = 'context_auth';

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function createInputs()
    {
        $this->add('person_id','hidden')->setFormValue($this->user->person_id);
        $this->add('role', 'radio')
            ->options($this->roles)->class('roles-div');

        // add-on permission inputs
        foreach ($this->addOns as $addOn => $label) {
            $this->add($addOn, 'boolean')->setFormValue($this->user->hasRole($addOn));
        }
    }

    public function initValues()
    {
        $userRoles = $this->user->getRoles();

        foreach ($this->roles as $role => $label) {
            if (in_array($role, $userRoles)) {
                $this->fill(['role' => $role]);
            }
        }
    }

    public function validate()
    {
        // nothing
    }

    public function commit()
    {
        $add = [];
        $remove = [];

        foreach ($this->roles as $role => $label) {
            if ($this->inputs['role']->getFormValue() === $role) {
                $add[] = $role;
            } else {
                $remove[] = $role;
            }

            if ($this->inputs['role']->getFormValue() === null) {
                foreach ($this->addOns as $addOn => $label) {
                    $remove[] = $addOn;
                }
            }
        }

        foreach ($this->addOns as $addOn => $label) {
            if ($this->inputs[$addOn]->getFormValue()) {
                $add[] = $addOn;
            } else {
                $remove[] = $addOn;
            }
        }

        $contact = ContactUpdater::updateOrCreateContact(['uwnetid' => $this->user->uwnetid]);

        $handler = new AuthorizationHandler();
        $handler->changeRoles($contact, $add, $remove);

        event(new UserModified($contact->uwnetid, user()->uwnetid));
    }
}
