<?php

$filename = 'budgets_'.date('Y-m-d').'.csv';

header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Expires: 0');
header('Pragma: public');
header('Content-type: text/csv');
header('Content-Disposition: attachment; filename='.$filename);

echoCsvRow([
    'Biennium',
    'Number',
    'Budget number',
    'Name',
    'Status code',
    'Status',
    'Purpose',
    'Begin date',
    'End date',
    'OrgCode',
    'Budget type',
    'Type description',
    'Budget class',
    'Class description',
    'Payroll unit code',
    'Indirect cost rate',
    'PI',
    'Administrative PI/Director',
    'Budget Manager',
    'Reconciler',
    'Food Policy',
]);

foreach ($budgets as $budget) {
    echoCsvRow([
        $budget->uw->BienniumYear,
        $budget->uw->BudgetNbr,
        ' ' . $budget->budgetno,
        $budget->uw->BudgetName,
        $budget->uw->BudgetStatus,
        $budget->uw->getStatusDescription(),
        $budget->purpose->note,
        eDate($budget->uw->getBeginDate()),
        eDate($budget->uw->getEndDate()),
        $budget->uw->OrgCode,
        $budget->uw->BudgetType,
        $budget->uw->getTypeDescription(),
        $budget->uw->BudgetClass,
        $budget->uw->BudgetClassDesc,
        $budget->uw->PayrollUnitCode,
        $budget->uw->AccountingIndirectCostRate,
        $budget->uw->PrincipalInvestigator,
        eFirstLast($budget->business),
        eFirstLast($budget->manager),
        eFirstLast($budget->reconciler),
        $budget->getFoodPolicy(),
    ]);
}
