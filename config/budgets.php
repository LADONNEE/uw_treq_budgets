<?php
/**
 * Criteria for UW Budget Data loaded from the EDW
 * @see App\Edw\BudgetDataSource
 */
return [
    'scope' => [
        'college-codes' => [
            '267' => 'INFORMATION SCHOOL',
        ],
        'org-codes' => [
            '0' => 'already covered by College Code',
        ],
        'budgets' => [
            '0' => 'already covered by College Code',
        ],
    ]
];
