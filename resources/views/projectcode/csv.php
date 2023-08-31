<?php

$filename = 'project_codes_'.date('Y-m-d').'.csv';

header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
header('Pragma: public');
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename='.$filename);

echoCsvRow([
    'Project Description',
    'Project Code',
    'Allocation Type / Frequency',
    'Purpose',
    'Pre-approval required',
    'Action Item',
    'Workday Code',
    'Workday Description',
    'Spend Authorizer',
    'Fiscal Person',
    'Created At',
    'Updated At'
]);

foreach ($projectcodes as $projectcode) {
    echoCsvRow([
        $projectcode->description,
        $projectcode->code,
        $projectcode->allocation_type_frequency,
        $projectcode->purpose,
        $projectcode->pre_approval_required,
        $projectcode->action_item,
        $projectcode->workday_code,
        $projectcode->workday_description,
        eFirstLast($projectcode->authorizer_person_id),
        eFirstLast($projectcode->fiscal_person_id),
        eDate($projectcode->created_at),
        eDate($projectcode->updated_at),
    ]);
}
