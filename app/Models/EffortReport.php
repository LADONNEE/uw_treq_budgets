<?php

namespace App\Models;

use App\Auth\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property integer $id
 * @property integer $faculty_contact_id
 * @property string $stage
 * @property string $type
 * @property integer $creator_contact_id
 * @property string $description
 * @property Carbon $start_at
 * @property Carbon $end_at
 * @property Carbon $notified_at
 * @property Carbon $revisit_at
 * @property Carbon $revisit_notified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property string $title
 *
 * ----------   Relationships   ----------
 * @property Contact $creator
 * @property Contact $faculty
 * @property Approval $approvals
 * @property EffortReportAllocation $effortReportAllocations
 * @property EffortReportNote $notes
 */
class EffortReport extends Model
{
    protected $fillable = [
        'faculty_contact_id',
        'stage',
        'type',
        'creator_contact_id',
        'description',
        'start_at',
        'end_at',
        'notified_at',
        'revisit_at',
        'revisit_notified_at',
    ];

    const STAGE_CANCELED = 'CANCELED';
    const STAGE_APPROVED = 'ENTERED IN WORKDAY';
    const STAGE_SENT_BACK = 'SENT BACK';
    const STAGE_BUDGET = 'BUDGET';
    const STAGE_DEFAULT_BUDGET = 'DEFAULT BUDGET';
    const STAGE_FACULTY = 'FACULTY';
    const STAGE_APPROVAL = 'APPROVAL';
    const STAGE_SUPERSEDED = 'SUPERSEDED';
    const STAGE_COEPAY = 'COE PAY';

    const PERIOD_SUMMER = 'SUMMER';
    const PERIOD_ACADEMIC_YEAR = 'ACADEMIC YEAR';

    const DATE_SUMMER_START = '-06-16';
    const DATE_SUMMER_END = '-09-15';
    const DATE_ACADEMIC_YEAR_START = '-09-16';
    const DATE_ACADEMIC_YEAR_END = '-06-15';

    public function faculty()
    {
        return $this->belongsTo(Contact::class, 'faculty_contact_id', 'id');
    }

    public function creator()
    {
        return $this->belongsTo(Contact::class, 'creator_contact_id', 'id');
    }

    public function approvals()
    {
        return $this->hasMany(Approval::class, 'report_id', 'id');
    }

    public function sentBackApproval()
    {
        return $this->approvals->firstWhere('response', Approval::RESPONSE_SENTBACK);
    }

    public function effortReportAllocations()
    {
        return $this->hasMany(EffortReportAllocation::class, 'report_id', 'id')
            ->orderBy('start_at')
            ->orderBy('end_at')
            ->orderBy('type', 'DESC')
            ->with('effortReport');
    }

    public function notes()
    {
        return $this->hasMany(EffortReportNote::class, 'report_id', 'id')
            ->orderBy('created_at', 'desc')
            ->with('contact');
    }

    public function notesInSection($section)
    {
        return $this->notes->filter(function($value, $key) use($section){
            return $value->section === $section;
        });
    }

    public function title()
    {
        return $this->faculty->lastname . ' -- ' . Str::title($this->type) . ' ' . eDate($this->start_at);
    }

    public function sendBack()
    {
        $this->stage = self::STAGE_SENT_BACK;
        $this->save();
    }

    public function canCancel(User $user)
    {
        if (self::isComplete()) {
            return false;
        }
        return Contact::personToContact($user->person_id) == $this->creator_contact_id || hasRole('cancel', $user);
    }

    public function cancel()
    {
        $this->stage = self::STAGE_CANCELED;
        $this->save();
    }

    public function isComplete()
    {
        return $this->stage === self::STAGE_APPROVED || $this->stage === self::STAGE_SENT_BACK || $this->stage === self::STAGE_SUPERSEDED;
    }

    public function isApproved()
    {
        return $this->stage === self::STAGE_APPROVED;
    }

    public function isSentBack()
    {
        return $this->stage === self::STAGE_SENT_BACK;
    }

    public function isCanceled()
    {
        return $this->stage === self::STAGE_CANCELED;
    }

    public function needsRevisit()
    {
        return $this->revisit_at !== null && $this->revisit_at <= now() && $this->revisit_notified_at === null;
    }

    public function checkSupersedes(EffortReport $effortReport)
    {
        $matchingReports = EffortReport::where('faculty_contact_id', $effortReport->faculty_contact_id)
            ->where('type', $effortReport->type)
            ->where('start_at', $effortReport->start_at)
            ->where('end_at', $effortReport->end_at)
            ->where('id', '<>', $effortReport->id)
            ->where('stage', '<>', self::STAGE_SENT_BACK)
            ->where('stage', '<>', self::STAGE_CANCELED)
            ->get();

        if ($matchingReports) {
            foreach ($matchingReports as $report) {
                $report->stage = self::STAGE_SUPERSEDED;
                $report->save();
            }
        }
    }

    public function getRelatedSupersededReports()
    {
        return EffortReport::where('faculty_contact_id', $this->faculty_contact_id)
            ->where('type', $this->type)
            ->where('start_at', $this->start_at)
            ->where('end_at', $this->end_at)
            ->where(function($query) {
                $query->where('stage', self::STAGE_SUPERSEDED);
                $query->orWhere('stage', self::STAGE_SENT_BACK);
                $query->orWhere('stage', self::STAGE_CANCELED);
            })
            ->where('id', '<>', $this->id)
            ->get();
    }

    public function getReportsThatWillBeSuperseded()
    {
        return EffortReport::where('faculty_contact_id', $this->faculty_contact_id)
            ->where('type', $this->type)
            ->where('start_at', $this->start_at)
            ->where('end_at', $this->end_at)
            ->where('id', '<>', $this->id)
            ->get();
    }

    public function getSupersededByReport()
    {
        return EffortReport::where('faculty_contact_id', $this->faculty_contact_id)
            ->where('type', $this->type)
            ->where('start_at', $this->start_at)
            ->where('end_at', $this->end_at)
            ->where('stage', '<>', self::STAGE_SUPERSEDED)
            ->where('stage', '<>', self::STAGE_SENT_BACK)
            ->where('stage', '<>', self::STAGE_CANCELED)
            ->where('id', '<>', $this->id)
            ->get();
    }

    public static function getCurrentPeriod($now)
    {
        if ($now >= $now->year . self::DATE_SUMMER_START && $now <= $now->year . self::DATE_SUMMER_END) {
            return self::PERIOD_SUMMER;
        } else {
            return self::PERIOD_ACADEMIC_YEAR;
        }
    }

    public static function getReportDateRange($type, $year)
    {
        if ($type === self::PERIOD_SUMMER) {
            return ['start_at' => Carbon::parse($year . self::DATE_SUMMER_START), 'end_at' => Carbon::parse($year . self::DATE_SUMMER_END)];
        } else {
            // academic year
            return ['start_at' => Carbon::parse($year . self::DATE_ACADEMIC_YEAR_START), 'end_at' => Carbon::parse($year . self::DATE_ACADEMIC_YEAR_END)->addYear()];
        }
    }

    public static function getAllocationsWithinRange($faculty_id, $reportRange)
    {
        return Allocation::where('faculty_contact_id', $faculty_id)
            ->where('start_at', '<=', $reportRange['end_at'])
            ->where('end_at', '>=', $reportRange['start_at'])
            ->get();
    }

    public static function getReportsByFaculty($facultyId)
    {
        return EffortReport::where('faculty_contact_id', $facultyId)
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public static function getLatestAndActiveReportsByFaculty($facultyId)
    {
        $reports = EffortReport::where('faculty_contact_id', $facultyId)
            ->where('stage', '!=', self::STAGE_SUPERSEDED)
            ->where('stage', '!=', self::STAGE_SENT_BACK)
            ->where('stage', '!=', self::STAGE_CANCELED)
            ->orderBy('created_at', 'DESC')
            ->get();

        if (count($reports) < 1) {
            return EffortReport::where('faculty_contact_id', $facultyId)
                ->where('stage', self::STAGE_SENT_BACK)
                ->latest()
                ->get();
        }

        return $reports;
    }

    public static function getApprovedSummerEffortReports($reportRange)
    {
        return EffortReport::select('effort_reports.*', 'contacts.firstname', 'contacts.lastname')
            ->join('contacts', 'effort_reports.faculty_contact_id', '=', 'contacts.id')
            ->where('effort_reports.start_at', '<=', $reportRange['end_at'])
            ->where('effort_reports.end_at', '>=', $reportRange['start_at'])
            ->where('effort_reports.stage', '=', EffortReport::STAGE_APPROVED)
            ->orderBy('contacts.lastname')
            ->get();
    }
}
