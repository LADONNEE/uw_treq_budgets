<?php

namespace App\Factories;

use App\Models\Allocation;
use App\Models\Approval;
use App\Models\Contact;
use App\Models\EffortReport;
use App\Utilities\DefaultFiscalPerson;

class ApprovalsFactory
{
    public static function generateApprovals($type, $year, $facultyId, $reportId = null, $response = null)
    {
        $approvals = self::getBudgetApprovals($facultyId, $type, $year);

        if (!in_array(self::getFacultyApproval($facultyId), $approvals)) {
            array_push($approvals, self::getFacultyApproval($facultyId));
        }

        if (!in_array(self::getCoePayApproval(), $approvals)) {
            array_push($approvals, self::getCoePayApproval());
        }

        foreach ($approvals as $approval) {
            $approval->report_id = $reportId;
            $approval->sequence = array_search($approval, $approvals);
            $approval->response = $response;
        }

        return $approvals;
    }

    private static function getBudgetApprovals($facultyId, $type, $year)
    {
        $budgetApprovals = [];
        $reportRange = EffortReport::getReportDateRange($type, $year);
        $allocations = Allocation::where('faculty_contact_id', $facultyId)
            ->where('start_at', '<=', $reportRange['end_at'])
            ->where('end_at', '>=', $reportRange['start_at'])
            ->get();
        $faculty = Contact::where('id', $facultyId)->first();
        $defaultFiscalPerson = DefaultFiscalPerson::defaultFiscalPerson();

        foreach ($allocations as $allocation) {
            $approval = new Approval;
            $approval->type = Approval::TYPE_BUDGET;

            if ($allocation->allocation_category === 'CROSS UNIT EFFORT') {
                $approval->name =
                    ($allocation->contact->fiscal_person_id
                        ? $allocation->contact->getFiscalPersonName()
                        : $defaultFiscalPerson->getFullName())  . ' (budget manager)';
                $approval->assigned_to_contact_id = $allocation->contact->fiscal_person_id ?? $defaultFiscalPerson->id;
            } else {
                $approval->name =
                    ($allocation->budget->fiscal_person_id
                        ? eFirstLast($allocation->budget->fiscal_person_id)
                        : $defaultFiscalPerson->getFullName())  . ' (budget manager)';
                $approval->assigned_to_contact_id = $allocation->budget->fiscal_person_id
                    ? Contact::personToContact($allocation->budget->fiscal_person_id)
                    : $defaultFiscalPerson->id;
            }

            if (!in_array($approval, $budgetApprovals)) {
                array_push($budgetApprovals, $approval);
            }
        }

        if ($faculty->default_budget_id) {
            // get default budget approver (must be last in budget approver list)
            $approval = new Approval;
            $approval->name = eFirstLast($faculty->defaultBudget->fiscal_person_id) . ' (default budget approver)';
            $approval->assigned_to_contact_id = Contact::personToContact($faculty->defaultBudget->fiscal_person_id);
            $approval->type = Approval::TYPE_DEFAULT_BUDGET;

            if (!in_array($approval, $budgetApprovals)) {
                array_push($budgetApprovals, $approval);
            }
        }

        return $budgetApprovals;
    }

    private static function getFacultyApproval($facultyId)
    {
        $approval = new Approval;
        $approval->assigned_to_contact_id = $facultyId;
        $approval->name = eFirstLast(Contact::where('id', $facultyId)->first())  . ' (faculty)';
        $approval->type = Approval::TYPE_FACULTY;

        return $approval;
    }

    private static function getCoePayApproval()
    {
        $approval = new Approval;
        $approval->assigned_to_contact_id = Contact::where('uwnetid', 'coenvpay')->pluck('id')[0];
        $approval->name = 'Coenvpay@uw.edu';
        $approval->type = Approval::TYPE_COENVPAY;

        return $approval;
    }
}
