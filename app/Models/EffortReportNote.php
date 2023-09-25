<?php

namespace App\Models;

use App\Auth\User;
use Carbon\Carbon;

/**
 * @property integer $id
 * @property integer $effort_report_id
 * @property string $section
 * @property string $note
 * @property integer $created_by
 * @property integer $updated_by
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * ---------   Relationships   ---------
 * @property EffortReport $effortReport
 * @property Contact $createdBy
 */
class EffortReportNote extends AbstractModel
{
    protected $table = 'effort_report_notes';
    protected $fillable = [
        'report_id',
        'note',
        'section',
        'created_by',
        'edited_by',
    ];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    const DEFAULT_SECTION = 'snapshot';

    public function editedBy(User $user)
    {
        if ($this->created_by) {
            $this->updated_by = $user->person_id;
        } else {
            $this->created_by = $user->person_id;
        }
    }

    public function edit($noteText)
    {
        if ($noteText === null || $noteText === '') {
            $this->delete();
        } else {
            $this->note = $noteText;
            $this->editedBy(user());
            $this->save();
        }
    }

    public function editedMessage()
    {
        if ($this->wasEdited()) {
            return sprintf('Edited by %s, %s', eFirstLast($this->updated_by), eDate($this->updated_at));
        }
        return '';
    }

    public function userCanEdit(User $user)
    {
        return $user->hasRole('budget:fiscal') || $user->person_id == $this->created_by;
    }

    public function wasEdited()
    {
        return (bool) $this->updated_by;
    }

    public function effortReport()
    {
        return $this->belongsTo(EffortReport::class, 'report_id', 'id');
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class, 'created_by', 'person_id');
    }
}
