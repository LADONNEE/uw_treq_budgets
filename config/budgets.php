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
            'not specified' => 'already covered by College Code',
        ],
        'budgets' => [
            'not specified' => 'already covered by College Code',
        ],
    ]
];
