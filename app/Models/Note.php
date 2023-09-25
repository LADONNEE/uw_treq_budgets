<?php

namespace App\Models;

use App\Auth\User;
use Carbon\Carbon;

/**
 * @property integer $id
 * @property integer $budget_id
 * @property string $section
 * @property string $note
 * @property integer $created_by
 * @property integer $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * ---------   Relationships   ---------
 * @property Budget $budget
 * @property Person $createdBy
 */
class Note extends AbstractModel
{
    protected $table = 'notes';
    protected $fillable = [
        'budget_id',
        'note',
        'section',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Set the Person this record was edited by
     * Stores as created_person_id or updated_person_id
     * @param integer $person_id
     */
    public function editedBy($person_id)
    {
        if ($this->created_by) {
            $this->updated_by = $person_id;
        } else {
            $this->created_by = $person_id;
        }
    }

    /**
     * Update and save the note body, mark note edited by user
     * If provided $note is empty, this Note record is deleted
     * @param string|null $note
     * @param User $user
     */
    public function edit($note, User $user)
    {
        if ($note === null || $note === '') {
            $this->delete();
        } else {
            $this->note = $note;
            $this->editedBy($user->person_id);
            $this->save();
        }
    }

    public function userCanEdit(User $user)
    {
        return $user->hasRole('budget:fiscal') || $user->person_id == $this->created_by;
    }

    public function budget()
    {
        return $this->belongsTo(Budget::class, 'budget_id', 'id');
    }

    public function createdBy()
    {
        return $this->belongsTo(Person::class, 'created_by', 'person_id');
    }
}
