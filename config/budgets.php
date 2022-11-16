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
            '2670000000' => 'already covered by College Code',
        ],
        'budgets' => [
            '000000' => 'already covered by College Code',
        ],
    ]
];
