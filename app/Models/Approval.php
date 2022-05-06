<?php

namespace App\Models;

use App\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * @property integer $id
 * @property integer $report_id
 * @property string $type
 * @property integer $sequence
 * @property integer $assigned_to_contact_id
 * @property Carbon $notified_at
 * @property integer $completed_by_contact_id
 * @property string $response
 * @property string $message
 * @property Carbon $responded_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * ----------   Relationships   ----------
 * @property EffortReport $effortReport
 * @property Contact $assignee
 * @property Contact $completer
 */
class Approval extends Model
{
    protected $fillable = [
        'report_id',
        'type',
        'sequence',
        'assigned_to_contact_id',
        'notified_at',
        'completed_by_contact_id',
        'response',
        'message',
        'responded_at',
    ];

    const TYPE_BUDGET = 'BUDGET';
    const TYPE_DEFAULT_BUDGET = 'DEFAULT BUDGET';
    const TYPE_FACULTY = 'FACULTY';
    const TYPE_APPROVAL = 'APPROVAL';
    const TYPE_UAAPAY = 'UAA PAY';

    const RESPONSE_APPROVED = 'APPROVED';
    const RESPONSE_SENTBACK = 'SENT BACK';
    const RESPONSE_COMPLETED = 'COMPLETED';
    const RESPONSE_PENDING = 'PENDING';
    const RESPONSE_REVISION = 'REVISION ONLY';

    public function effortReport()
    {
        return $this->belongsTo(EffortReport::class, 'report_id', 'id');
    }

    public function canComplete(User $user)
    {
        if ($this->isComplete()) {
            return false;
        }
        return Contact::personToContact($user->person_id) === $this->assigned_to_contact_id || hasRole('act-on-behalf', $user);
    }

    public function canCompleteWorkday(User $user)
    {
        if ($this->isComplete()) {
            return false;
        }
        return Contact::personToContact($user->person_id) === $this->assigned_to_contact_id || hasRole('act-on-behalf', $user) || hasRole('workday', $user);
    }

    public function hasWorkdayRole(User $user)
    {
        return hasRole('workday', $user);
    }

    public function canDelete(User $user)
    {
        if ($this->isComplete()) {
            return false;
        }
        return hasRole('delete-tasks', $user);
    }

    public function isApproved()
    {
        return $this->response === self::RESPONSE_APPROVED || $this->response === self::RESPONSE_REVISION;
    }

    public function isComplete()
    {
        return $this->responded_at;
    }

    public function isSentBack()
    {
        return $this->response === self::RESPONSE_SENTBACK;
    }

    public function isUaapay()
    {
        return $this->type === self::TYPE_UAAPAY;
    }

    public function isReadyToApprove()
    {
        return $this->type === $this->effortReport->stage;
    }

    public function assignee()
    {
        return $this->belongsTo(Contact::class, 'assigned_to_contact_id', 'id');
    }

    public function completer()
    {
        return $this->belongsTo(Contact::class, 'completed_by_contact_id', 'id');
    }

    public function firstPending($reportId)
    {
        return $this->where('report_id', $reportId)
            ->where('response', self::RESPONSE_PENDING)
            ->orderBy('sequence')
            ->first();
    }
}
